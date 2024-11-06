<?php
// การเชื่อมต่อฐานข้อมูล
$dsn = 'mysql:host=localhost;dbname=recipe_database;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // รับค่าหมวดหมู่และจำนวนสูตรที่จะดึง
    $category = $_GET['category'] ?? 'main';
    $limit = (int)($_GET['limit'] ?? 10);

    // สร้าง SQL สำหรับดึงข้อมูลสูตรอาหารที่มีเรตติ้งสูงสุดตามหมวดหมู่
    $stmt = $pdo->prepare("
        SELECT recipe_name, rating, source, image_path
        FROM recipe
        WHERE food_category = :category
        ORDER BY rating DESC, created_at DESC
        LIMIT $limit
    ");
    $stmt->bindValue(':category', $category);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    // แปลงข้อมูลให้เป็น JSON
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($recipes);

} catch (PDOException $e) {
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' . $e->getMessage()]);
}
?>
