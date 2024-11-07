<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$dbname = 'recipe_database';
$username = 'root';
$password = '';

// ตั้งค่า Content-Type ให้เป็น JSON เสมอ
header('Content-Type: application/json');

// เชื่อมต่อฐานข้อมูล
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ดึงข้อมูลสูตรอาหารล่าสุด 4 รายการ
    $query = "SELECT recipe_name, rating, source, created_at, image_path FROM recipe ORDER BY created_at DESC LIMIT 4";
    $stmt = $pdo->query($query);

    // แปลงผลลัพธ์เป็น JSON
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($recipes);

} catch (PDOException $e) {
    // ส่งข้อมูล JSON ในกรณีที่เกิดข้อผิดพลาด
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
?>
