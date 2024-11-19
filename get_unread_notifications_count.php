<?php
header('Content-Type: application/json');

try {
    // เชื่อมต่อฐานข้อมูล
    $pdo = new PDO('mysql:host=localhost;dbname=recipe_database;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    session_start();
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(['error' => 'กรุณาเข้าสู่ระบบ']);
        exit();
    }

    // ตรวจสอบว่ามีคอลัมน์ is_read หรือไม่
    $stmtCheckColumn = $pdo->query("SHOW COLUMNS FROM notifications LIKE 'is_read'");
    $hasIsReadColumn = $stmtCheckColumn->rowCount() > 0;

    if (!$hasIsReadColumn) {
        echo json_encode(['error' => 'คอลัมน์ is_read ไม่พบในตาราง notifications']);
        exit();
    }

    // ดึงจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน
    $stmt = $pdo->prepare("SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = :user_id AND is_read = 0");
    $stmt->execute([':user_id' => $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['unread_count' => $result['unread_count'] ?? 0]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
