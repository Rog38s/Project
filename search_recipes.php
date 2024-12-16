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
    $searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

    if (empty($searchQuery)) {
        echo json_encode(['error' => 'กรุณาใส่คำค้นหา']);
        exit;
    }

    // ค้นหาข้อมูลในฐานข้อมูลเฉพาะชื่อสูตร พร้อมแสดงจำนวนคอมเมนต์
    $stmt = $pdo->prepare("
        SELECT 
            r.id, 
            r.recipe_name, 
            r.rating, 
            r.source, 
            r.created_at, 
            r.image_path, 
            COUNT(c.id) AS comment_count
        FROM recipe r
        LEFT JOIN comments c ON r.id = c.recipe_id
        WHERE r.recipe_name LIKE :query
        GROUP BY r.id
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([':query' => "%$searchQuery%"]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ามีผลลัพธ์หรือไม่
    if (empty($recipes)) {
        echo json_encode(['status' => 'no_results', 'message' => 'ไม่พบผลลัพธ์ที่ตรงกับคำค้นหา']);
    } else {
        echo json_encode(['status' => 'success', 'data' => $recipes]);
    }
    

} catch (Exception $e) {
    // ส่งข้อความแสดงข้อผิดพลาดเมื่อมีปัญหากับฐานข้อมูล
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการค้นหา: ' . $e->getMessage()]);
}
