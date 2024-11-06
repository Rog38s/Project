<?php
header('Content-Type: application/json');
require 'db_connection.php'; // สมมติว่ามีการตั้งค่าการเชื่อมต่อฐานข้อมูล

try {
    $stmt = $pdo->prepare("SELECT recipe_name, image_path, rating, source FROM recipe ORDER BY created_at DESC LIMIT 4");
    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($recipes);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "ไม่สามารถดึงข้อมูลสูตรอาหารได้: " . $e->getMessage()]);
}
?>
