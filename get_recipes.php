<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$dbname = 'recipe_database';
$username = 'root';
$password = '';

// ตั้งค่า Content-Type ให้เป็น JSON
header('Content-Type: application/json');

try {
    // เชื่อมต่อฐานข้อมูล
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // รับพารามิเตอร์จาก URL
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $type = isset($_GET['type']) ? $_GET['type'] : 'new'; // default เป็น 'new'
    $recipe_id = isset($_GET['recipe_id']) ? intval($_GET['recipe_id']) : null;

    // ถ้ามี recipe_id ให้ดึงข้อมูลสูตรเดี่ยว
    if ($recipe_id) {
        $query = "
            SELECT 
                r.id, 
                r.recipe_name, 
                r.ingredients, 
                r.steps, 
                r.rating, 
                r.source, 
                r.created_at, 
                r.updated_at, 
                r.image_path,
                COUNT(c.id) AS comment_count
            FROM recipe r
            LEFT JOIN comments c ON r.id = c.recipe_id
            WHERE r.id = :recipe_id
            GROUP BY r.id
        ";
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

    // ตรวจสอบว่า category ถูกต้องหรือไม่
    if ($category !== null && !in_array($category, ['อาหารคาว', 'ของหวาน'])) {
        echo json_encode(['error' => 'ประเภทอาหารไม่ถูกต้อง']);
        exit;
    }

    // เลือก SQL ตามประเภท type
    switch ($type) {
        case 'new':
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
                " . ($category ? "WHERE r.food_category = :food_category" : "") . "
                GROUP BY r.id
                ORDER BY r.created_at DESC
                LIMIT 4
            ";
            break;
        case 'popular':
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
                " . ($category ? "WHERE r.food_category = :food_category" : "") . "
                GROUP BY r.id
                ORDER BY r.rating DESC, r.created_at DESC
                LIMIT 10
            ";
            break;
        case 'ancient':
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
                " . ($category ? "WHERE r.food_category = :food_category" : "") . "
                GROUP BY r.id
                ORDER BY r.created_at ASC
                LIMIT 10
            ";
            break;
        default:
            echo json_encode(['error' => 'ประเภทข้อมูลไม่ถูกต้อง']);
            exit;
    }

    // เตรียมและรันคำสั่ง SQL
    $stmt = $pdo->prepare($query);

    // ถ้ามีการกรอง category
    if ($category) {
        $stmt->execute(['food_category' => $category]);
    } else {
        $stmt->execute();
    }

    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ส่งข้อมูลกลับในรูปแบบ JSON
    echo json_encode($recipes);

} catch (PDOException $e) {
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
