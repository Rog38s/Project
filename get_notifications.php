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

    // ดึงข้อมูลการแจ้งเตือนพร้อมตรวจสอบ recipe_id, recipe_name และ user_id
    $stmt = $pdo->prepare("
        SELECT 
            n.id AS notification_id,
            n.recipe_name,
            n.message,
            n.created_at,
            r.id AS recipe_id,
            r.user_id AS owner_id
        FROM notifications n
        LEFT JOIN recipe r ON n.recipe_name = r.recipe_name
        WHERE n.user_id = :user_id
        ORDER BY n.created_at DESC
    ");

    $stmt->execute([':user_id' => $user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ปรับแต่งผลลัพธ์เพื่อรวมลิงก์ไปยัง my_recipe_edit.html หรือ recipe.html
    $formattedNotifications = array_map(function ($notification) use ($user_id) {
        if ($notification['recipe_id']) {
            // ตรวจสอบว่าผู้ใช้เป็นเจ้าของสูตรหรือไม่
            $notification['recipe_link'] = ($notification['owner_id'] == $user_id)
                ? "my_recipe_edit.html?recipe_id=" . $notification['recipe_id']
                : "recipe.html?recipe_id=" . $notification['recipe_id'];
        } else {
            $notification['recipe_link'] = null; // ไม่มี recipe_id
        }
        return $notification;
    }, $notifications);

    echo json_encode($formattedNotifications);
} catch (PDOException $e) {
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
