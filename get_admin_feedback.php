<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=recipe_database;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $recipe_id = $_GET['recipe_id'] ?? null;

    if (!$recipe_id) {
        echo json_encode(['error' => 'Invalid recipe ID']);
        exit();
    }

    $stmt = $pdo->prepare("
        SELECT message, created_at 
        FROM notifications 
        WHERE recipe_name = (SELECT recipe_name FROM recipe WHERE id = :recipe_id)
        ORDER BY created_at DESC
    ");
    $stmt->execute([':recipe_id' => $recipe_id]);

    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($feedbacks);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
