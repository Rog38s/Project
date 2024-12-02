<?php
header('Content-Type: application/json');

if (!isset($_GET['recipe_id'])) {
    echo json_encode(['error' => 'ไม่พบ recipe_id']);
    exit;
}

$recipe_id = intval($_GET['recipe_id']);

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=recipe_database;charset=utf8",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

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

    echo json_encode($comments ?: []);

} catch (Exception $e) {
    echo json_encode(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
