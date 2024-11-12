<?php
session_start();

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'กรุณาเข้าสู่ระบบก่อนทำการรายงาน']);
    exit;
}

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$dbname = 'recipe_database';
$username = 'root'; // เปลี่ยน username หากจำเป็น
$password = '';     // เปลี่ยน password หากจำเป็น

// สร้างการเชื่อมต่อฐานข้อมูล
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' . $e->getMessage()]);
    exit;
}

// ตรวจสอบว่าการร้องขอเป็นแบบ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = $_POST['recipe_id'];
    $report_text = $_POST['report_text'];
    $user_id = $_SESSION['user_id'];
    
    try {
        // เตรียมคำสั่ง SQL สำหรับการบันทึกรายงาน
        $stmt = $pdo->prepare("INSERT INTO reports (recipe_id, user_id, report_text, report_date) 
                               VALUES (?, ?, ?, NOW())");
        
        // รันคำสั่ง SQL
        if ($stmt->execute([$recipe_id, $user_id, $report_text])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'เกิดข้อผิดพลาดในระบบฐานข้อมูล: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
