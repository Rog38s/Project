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

    // ตรวจสอบว่าผู้ใช้ล็อกอินและหมวดหมู่ถูกส่งเข้ามาหรือไม่
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'กรุณาเข้าสู่ระบบ']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $category = isset($_GET['category']) ? trim($_GET['category']) : null;
    $searchQuery = isset($_GET['query']) ? trim($_GET['query']) : null;

    // ตรวจสอบว่าหมวดหมู่ถูกกำหนดหรือไม่
    if (!$category) {
        echo json_encode(['error' => 'กรุณาระบุหมวดหมู่']);
        exit;
    }

    // คิวรีข้อมูลสูตรอาหารตามเงื่อนไข
    $query = "
        SELECT 
            r.id, 
            r.recipe_name, 
            r.rating, 
            r.source, 
            r.created_at, 
            r.updated_at, 
            r.image_path, 
            COUNT(c.id) AS comment_count
        FROM recipe r
        LEFT JOIN comments c ON r.id = c.recipe_id
        WHERE r.food_category = :category 
          AND r.user_id = :user_id
    ";

    // เพิ่มเงื่อนไขการค้นหาถ้ามีคำค้นหา
    if ($searchQuery) {
        $query .= " AND (r.recipe_name LIKE :query OR r.ingredients LIKE :query)";
    }

    $query .= " GROUP BY r.id ORDER BY r.created_at DESC";

    $stmt = $pdo->prepare($query);

    // ผูกค่าพารามิเตอร์
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':user_id', $user_id);

    if ($searchQuery) {
        $stmt->bindValue(':query', "%$searchQuery%");
    }

    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าพบข้อมูลหรือไม่
    if (empty($recipes)) {
        echo json_encode(['message' => 'ไม่พบสูตรอาหาร']);
    } else {
        echo json_encode($recipes);
    }

} catch (PDOException $e) {
    // ส่งข้อความแสดงข้อผิดพลาด
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' . $e->getMessage()]);
}
