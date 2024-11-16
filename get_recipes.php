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

    // รับค่า category, type, และ recipe_id จากพารามิเตอร์ใน URL
    $category = isset($_GET['category']) ? $_GET['category'] : null; // ตั้งค่าเริ่มต้นเป็น null
    $type = isset($_GET['type']) ? $_GET['type'] : 'new'; // default เป็น 'new'
    $recipe_id = isset($_GET['recipe_id']) ? intval($_GET['recipe_id']) : null;

    // ตรวจสอบว่ามีการส่ง recipe_id มาหรือไม่
    if ($recipe_id) {
        // คิวรีดึงข้อมูลสูตรอาหาร
        $query = "SELECT id, recipe_name, ingredients, steps, rating, source, created_at, updated_at, image_path 
                  FROM recipe 
                  WHERE id = :recipe_id";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['recipe_id' => $recipe_id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        // ตรวจสอบว่าพบข้อมูลหรือไม่
        if ($recipe) {
            echo json_encode($recipe);
        } else {
            echo json_encode(['error' => 'ไม่พบสูตรอาหารที่ระบุ']);
        }
        exit;
    }

    // ตรวจสอบว่า category ที่รับมาเป็นประเภทที่ถูกต้องหรือไม่ (หากไม่ใช่ null)
    if ($category !== null && !in_array($category, ['อาหารคาว', 'ของหวาน'])) {
        echo json_encode(['error' => 'ประเภทอาหารไม่ถูกต้อง']);
        exit;
    }

    // เลือกเงื่อนไขการดึงข้อมูลตาม type
    switch ($type) {
        case 'new':
            $query = "SELECT id, recipe_name, rating, source, created_at, updated_at, image_path 
                      FROM recipe 
                      " . ($category ? "WHERE food_category = :food_category" : "") . "
                      ORDER BY created_at DESC 
                      LIMIT 4";
            break;
        case 'popular':
            $query = "SELECT id, recipe_name, rating, source, created_at, updated_at, image_path 
                      FROM recipe 
                      " . ($category ? "WHERE food_category = :food_category" : "") . "
                      ORDER BY rating DESC, created_at DESC
                      LIMIT 10";
            break;
        case 'ancient':
            $query = "SELECT id, recipe_name, rating, source, created_at, updated_at, image_path 
                      FROM recipe 
                      " . ($category ? "WHERE food_category = :food_category" : "") . "
                      ORDER BY rating DESC, created_at ASC
                      LIMIT 10";
            break;
        default:
            echo json_encode(['error' => 'ประเภทข้อมูลไม่ถูกต้อง']);
            exit;
    }

    // เตรียมและรันคำสั่ง SQL
    $stmt = $pdo->prepare($query);

    // ถ้ามีการกำหนด category ให้ bind ค่า
    if ($category) {
        $stmt->execute(['food_category' => $category]);
    } else {
        $stmt->execute();
    }

    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ส่งข้อมูลกลับในรูปแบบ JSON
    echo json_encode($recipes);

} catch (PDOException $e) {
    // ส่งข้อมูล JSON ในกรณีที่เกิดข้อผิดพลาด
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
?>
