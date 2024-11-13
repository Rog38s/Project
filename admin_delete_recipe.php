<?php
// เริ่มต้น session
session_start();

// ตรวจสอบว่าผู้ใช้เป็น admin
if (!isset($_SESSION['user_id'])) {
    // เชื่อมต่อกับฐานข้อมูล user_management เพื่อตรวจสอบ role
    $user_db = new PDO("mysql:host=localhost;dbname=user_management;charset=utf8", "root", "");
    $stmt = $user_db->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || $user['role'] !== 'admin') {
        $response = [
            'success' => false,
            'error' => 'Unauthorized access'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// การเชื่อมต่อฐานข้อมูล recipe_database
try {
    $conn = new PDO("mysql:host=localhost;dbname=recipe_database;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // เริ่ม transaction
    $conn->beginTransaction();

    // รับค่า recipe_id จาก POST request
    $recipe_id = isset($_POST['recipe_id']) ? intval($_POST['recipe_id']) : 0;

    if ($recipe_id <= 0) {
        throw new Exception("Invalid recipe ID");
    }

    // 1. ลบรูปภาพที่เกี่ยวข้องกับสูตรอาหาร
    $stmt = $conn->prepare("SELECT image_path FROM recipe WHERE id = ?");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($recipe && $recipe['image_path'] && file_exists($recipe['image_path'])) {
        unlink($recipe['image_path']);
    }

    // 2. ลบข้อมูลตามลำดับจากตารางที่เกี่ยวข้อง
    // ลบรายงาน
    $stmt = $conn->prepare("DELETE FROM reports WHERE recipe_id = ?");
    $stmt->execute([$recipe_id]);

    // ลบความคิดเห็น
    $stmt = $conn->prepare("DELETE FROM comments WHERE recipe_id = ?");
    $stmt->execute([$recipe_id]);

    // สุดท้าย ลบสูตรอาหาร
    $stmt = $conn->prepare("DELETE FROM recipe WHERE id = ?");
    $stmt->execute([$recipe_id]);

    // ยืนยัน transaction
    $conn->commit();

    $response = [
        'success' => true,
        'message' => 'ลบสูตรอาหารและข้อมูลที่เกี่ยวข้องเรียบร้อยแล้ว'
    ];

} catch (Exception $e) {
    // ถ้าเกิดข้อผิดพลาด ให้ rollback การทำงานทั้งหมด
    if (isset($conn)) {
        $conn->rollBack();
    }
    
    $response = [
        'success' => false,
        'error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ];
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn = null;

// ส่ง response กลับเป็น JSON
header('Content-Type: application/json');
echo json_encode($response);
?>