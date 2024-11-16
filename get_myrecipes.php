<?php
// ตั้งค่าการตอบกลับเป็น JSON
header('Content-Type: application/json');
session_start();

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$dbname = 'recipe_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ตรวจสอบว่า user_id และ category ถูกส่งเข้ามา
    if (isset($_SESSION['user_id']) && isset($_GET['category'])) {
        $user_id = $_SESSION['user_id'];
        $category = $_GET['category'];

        // ดึงข้อมูลสูตรอาหารตามหมวดหมู่และผู้ใช้ที่ล็อกอิน
        $stmt = $pdo->prepare("
            SELECT id, recipe_name, rating, source, created_at, updated_at, image_path 
            FROM recipe 
            WHERE food_category = :category 
            AND user_id = :user_id 
            ORDER BY created_at DESC
        ");
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // ส่งข้อมูลออกเป็น JSON
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($recipes);

    } else {
        echo json_encode(['error' => 'ไม่ได้ระบุหมวดหมู่หรือผู้ใช้ที่ล็อกอิน']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' . $e->getMessage()]);
}
?>
