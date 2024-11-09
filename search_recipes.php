<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$dbname = 'recipe_database';
$username = 'root';
$password = '';

// ตั้งค่า Content-Type ให้เป็น JSON
header('Content-Type: application/json');

// เชื่อมต่อฐานข้อมูล
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // รับค่าคำค้นหาจาก URL
    $searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

    // ค้นหาข้อมูลในฐานข้อมูล
    $stmt = $pdo->prepare("SELECT id, recipe_name, rating, source, created_at, image_path 
                           FROM recipe 
                           WHERE recipe_name LIKE :query");
    $stmt->execute([':query' => "%$searchQuery%"]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ส่งผลลัพธ์เป็น JSON
    echo json_encode($recipes);

} catch (Exception $e) {
    // ส่งข้อความแสดงข้อผิดพลาดเมื่อมีปัญหากับฐานข้อมูล
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการค้นหา: ' . $e->getMessage()]);
}
?>
