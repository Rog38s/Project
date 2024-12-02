<?php
session_start();

// ตรวจสอบสิทธิ์เข้าถึงของผู้ใช้ (ต้องเป็นแอดมินเท่านั้น)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

try {
    // เชื่อมต่อกับฐานข้อมูล recipe_database
    $recipe_db = new PDO('mysql:host=localhost;dbname=recipe_database', 'root', '');
    $recipe_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // เชื่อมต่อกับฐานข้อมูล user_management
    $user_db = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
    $user_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // คำสั่ง SQL เพื่อดึงข้อมูลรายงาน พร้อมชื่อสูตร ชื่อผู้รายงาน และ path รูปภาพ
    $query = "
        SELECT 
            r.id as report_id,
            r.recipe_id,
            r.user_id as reporter_id,
            r.report_text,
            DATE(r.report_date) AS report_date,      -- แยกเฉพาะวันที่
            TIME(r.report_date) AS report_time,      -- แยกเฉพาะเวลา
            rc.recipe_name,
            rc.image_path AS recipe_image,
            u.username as reporter_name
        FROM recipe_database.reports r
        INNER JOIN recipe_database.recipe rc ON r.recipe_id = rc.id
        INNER JOIN user_management.users u ON r.user_id = u.id
        ORDER BY r.report_date DESC
    ";

    $stmt = $recipe_db->prepare($query);
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reports);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
