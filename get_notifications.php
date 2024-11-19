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

    // ตรวจสอบว่าตาราง notifications มี recipe_id หรือไม่
    $stmtCheckColumn = $pdo->query("SHOW COLUMNS FROM notifications LIKE 'recipe_id'");
    $hasRecipeIdColumn = $stmtCheckColumn->rowCount() > 0;

    // หากไม่มี recipe_id ให้ดึงข้อมูลการแจ้งเตือนโดยไม่ JOIN
    if (!$hasRecipeIdColumn) {
        $stmt = $pdo->prepare("
            SELECT 
                n.message,
                n.created_at
            FROM notifications n
            WHERE n.user_id = :user_id
            ORDER BY n.created_at DESC
        ");
    } else {
        // ดึงข้อมูลการแจ้งเตือนพร้อมข้อมูล recipe (ถ้ามี recipe_id)
        $stmt = $pdo->prepare("
            SELECT 
                n.message,
                n.created_at,
                COALESCE(r.image_path, 'img/default_recipe.png') AS image_path
            FROM notifications n
            LEFT JOIN recipe r ON n.recipe_id = r.id
            WHERE n.user_id = :user_id
            ORDER BY n.created_at DESC
        ");
    }

    $stmt->execute([':user_id' => $user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($notifications);
} catch (PDOException $e) {
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
