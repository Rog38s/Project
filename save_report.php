<?php
// save_report.php
session_start();
header('Content-Type: application/json');

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'กรุณาเข้าสู่ระบบก่อนรายงาน']);
    exit;
}

try {
    // เชื่อมต่อฐานข้อมูล
    $pdo = new PDO(
        "mysql:host=localhost;dbname=recipe_database;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

    // ตรวจสอบข้อมูลที่ส่งมา
    if (!isset($_POST['recipe_id']) || !isset($_POST['report_text']) || 
        empty($_POST['recipe_id']) || empty($_POST['report_text'])) {
        throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
    }

    // ทำความสะอาดข้อมูล
    $recipe_id = filter_var($_POST['recipe_id'], FILTER_VALIDATE_INT);
    $report_text = trim($_POST['report_text']);
    $user_id = $_SESSION['user_id'];

    if (!$recipe_id) {
        throw new Exception('รหัสสูตรอาหารไม่ถูกต้อง');
    }

    // ตรวจสอบว่าสูตรอาหารมีอยู่จริง
    $stmt = $pdo->prepare("SELECT id FROM recipe WHERE id = ?");
    $stmt->execute([$recipe_id]);
    if (!$stmt->fetch()) {
        throw new Exception('ไม่พบสูตรอาหารที่ระบุ');
    }

    // ตรวจสอบว่าผู้ใช้เคยรายงานสูตรนี้ไปแล้วหรือไม่
    $stmt = $pdo->prepare("
        SELECT report_id 
        FROM reports 
        WHERE recipe_id = ? AND user_id = ? AND status != 'resolved'
    ");
    $stmt->execute([$recipe_id, $user_id]);
    if ($stmt->fetch()) {
        throw new Exception('คุณได้รายงานสูตรอาหารนี้ไปแล้ว และยังอยู่ระหว่างการตรวจสอบ');
    }

    // บันทึกการรายงาน
    $stmt = $pdo->prepare("
        INSERT INTO reports (recipe_id, user_id, report_text)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$recipe_id, $user_id, $report_text]);

    echo json_encode(['success' => true, 'message' => 'บันทึกการรายงานเรียบร้อยแล้ว']);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล']);
    error_log($e->getMessage());
}
?>