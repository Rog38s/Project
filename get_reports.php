<?php
// get_reports.php - สำหรับดึงข้อมูลรายงานของสูตรอาหาร (สำหรับผู้ดูแลระบบ)
session_start();
header('Content-Type: application/json');

// ตรวจสอบสิทธิ์ผู้ดูแลระบบ
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    http_response_code(403);
    echo json_encode(['error' => 'ไม่มีสิทธิ์เข้าถึงข้อมูล']);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=recipe_database;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

    // ดึงข้อมูลรายงานทั้งหมดพร้อมข้อมูลที่เกี่ยวข้อง
    $query = "
        SELECT 
            r.report_id,
            r.report_text,
            r.status,
            r.created_at,
            rec.recipe_name,
            rec.id as recipe_id,
            u.username as reporter_name
        FROM reports r
        JOIN recipes rec ON r.recipe_id = rec.id
        JOIN users u ON r.user_id = u.id
        ORDER BY 
            CASE r.status
                WHEN 'pending' THEN 1
                WHEN 'reviewing' THEN 2
                WHEN 'resolved' THEN 3
            END,
            r.created_at DESC
    ";

    $stmt = $pdo->query($query);
    $reports = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'reports' => $reports
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล']);
    error_log($e->getMessage());
}