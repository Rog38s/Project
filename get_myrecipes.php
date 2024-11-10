<?php
// ตั้งค่าการตอบกลับเป็น JSON
header('Content-Type: application/json');

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$dbname = 'recipe_database'; // ชื่อฐานข้อมูล
$username = 'root'; // ใส่ชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = ''; // ใส่รหัสผ่านของฐานข้อมูล

try {
    // สร้างการเชื่อมต่อฐานข้อมูล
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ตรวจสอบว่ามีการส่งค่า category เข้ามาหรือไม่
    if (isset($_GET['category'])) {
        $category = $_GET['category'];

        // ดึงข้อมูลสูตรอาหารตามหมวดหมู่ที่ระบุ
        $stmt = $pdo->prepare("SELECT id, recipe_name, rating, source, created_at, image_path FROM recipe WHERE food_category = :category ORDER BY created_at DESC");
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        
        // ดึงข้อมูลและส่งออกเป็น JSON
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($recipes);

    } else {
        // หากไม่มีการส่งค่า category เข้ามา
        echo json_encode(['error' => 'ไม่ได้ระบุหมวดหมู่ของสูตรอาหาร']);
    }

} catch (PDOException $e) {
    // ข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' . $e->getMessage()]);
}
?>
