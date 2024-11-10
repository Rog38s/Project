<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to file
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

if (!isset($_GET['recipe_id'])) {
    error_log("No recipe_id provided");
    echo json_encode(['error' => 'ไม่พบ recipe_id']);
    exit;
}

$recipe_id = intval($_GET['recipe_id']);
error_log("Fetching comments for recipe_id: " . $recipe_id);

try {
    // เชื่อมต่อฐานข้อมูลโดยตรง
    $pdo = new PDO(
        "mysql:host=localhost;dbname=recipe_database;charset=utf8",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // ตรวจสอบว่า recipe_id มีอยู่จริง
    $check_recipe = $pdo->prepare("SELECT id FROM recipe WHERE id = ?");
    $check_recipe->execute([$recipe_id]);
    if (!$check_recipe->fetch()) {
        throw new Exception("ไม่พบสูตรอาหารที่ระบุ");
    }

    $stmt = $pdo->prepare("
        SELECT 
            c.id,
            c.rating,
            c.comment_text,
            c.created_at,
            u.username
        FROM comments c
        JOIN user_management.users u ON c.user_id = u.id
        WHERE c.recipe_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$recipe_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ถ้าไม่มีความคิดเห็น ส่งarray ว่างกลับไป
    echo json_encode($comments ?: []);

} catch (Exception $e) {
    error_log("Error in get_comments.php: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>