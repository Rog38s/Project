<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=recipe_database;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    session_start();
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(['error' => 'กรุณาเข้าสู่ระบบ']);
        exit();
    }

    // ดึงข้อมูลการแจ้งเตือน พร้อมตรวจสอบ recipe_name
    $stmt = $pdo->prepare("
        SELECT 
            n.recipe_name,
            n.message,
            n.created_at
        FROM notifications n
        WHERE n.user_id = :user_id
        ORDER BY n.created_at DESC
    ");

    $stmt->execute([':user_id' => $user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ปรับแต่งผลลัพธ์เพื่อแสดงเฉพาะ message พร้อม recipe_name (ถ้ามี)
    $formattedNotifications = array_map(function ($notification) {
        if (!empty($notification['recipe_name'])) {
            $notification['message'] = $notification['recipe_name'] . ' - ' . $notification['message'];
        }
        unset($notification['recipe_name']); // ลบ key recipe_name เพื่อไม่ต้องแสดงซ้ำ
        return $notification;
    }, $notifications);

    echo json_encode($formattedNotifications);
} catch (PDOException $e) {
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
