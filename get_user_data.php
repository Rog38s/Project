<?php
header('Content-Type: application/json'); // แจ้งให้ทราบว่าเป็น JSON

try {
    // เชื่อมต่อฐานข้อมูล
    $pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', ''); // เปลี่ยนชื่อฐานข้อมูลเป็น user_management
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // รับค่า user_id จาก session
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'กรุณาล็อกอินก่อน']);
        exit;
    }
    $user_id = $_SESSION['user_id'];  // ใช้ user_id จากเซสชัน

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT id, username, email, phone, gender, birth_date, profile_image FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ส่งข้อมูลเป็น JSON กลับไปยัง frontend
    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'ไม่พบผู้ใช้']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>
