<?php
session_start();
require_once 'config.php';

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'กรุณาเข้าสู่ระบบก่อนทำการรายงาน']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = $_POST['recipe_id'];
    $report_text = $_POST['report_text'];
    $user_id = $_SESSION['user_id'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO reports (recipe_id, user_id, report_text, report_date) 
                              VALUES (?, ?, ?, NOW())");
        
        if ($stmt->execute([$recipe_id, $user_id, $report_text])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'เกิดข้อผิดพลาดในระบบฐานข้อมูล']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>