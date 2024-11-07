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

    // รับค่า category และ type จากพารามิเตอร์ใน URL
    $category = isset($_GET['category']) ? $_GET['category'] : 'อาหารคาว';  // default เป็น 'อาหารคาว'
    $type = isset($_GET['type']) ? $_GET['type'] : 'new';  // default เป็น 'new'

    // ตรวจสอบว่า category ที่รับมาเป็นประเภทที่ถูกต้องหรือไม่
    if (!in_array($category, ['อาหารคาว', 'ของหวาน'])) {
        echo json_encode(['error' => 'ประเภทอาหารไม่ถูกต้อง']);
        exit;
    }

    // เลือกเงื่อนไขการดึงข้อมูลตาม type
    switch ($type) {
        case 'new':
            $query = "SELECT id, recipe_name, rating, source, created_at, image_path 
                      FROM recipe 
                      WHERE food_category = :food_category 
                      ORDER BY created_at DESC 
                      LIMIT 10";
            break;
        case 'popular':
            $query = "SELECT id, recipe_name, rating, source, created_at, image_path 
                      FROM recipe 
                      WHERE food_category = :food_category 
                      ORDER BY rating DESC, created_at DESC 
                      LIMIT 10";
            break;
        case 'ancient':
            $query = "SELECT id, recipe_name, rating, source, created_at, image_path 
                      FROM recipe 
                      WHERE food_category = :food_category 
                      ORDER BY created_at ASC 
                      LIMIT 10";
            break;
        default:
            echo json_encode(['error' => 'ประเภทข้อมูลไม่ถูกต้อง']);
            exit;
    }

    // ดึงข้อมูลจากฐานข้อมูล
    $stmt = $pdo->prepare($query);
    $stmt->execute(['food_category' => $category]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ส่งข้อมูลกลับในรูปแบบ JSON
    echo json_encode($recipes);

} catch (PDOException $e) {
    // ส่งข้อมูล JSON ในกรณีที่เกิดข้อผิดพลาด
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
?>
