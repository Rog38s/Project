<?php
$host = 'localhost'; // ชื่อโฮสต์ฐานข้อมูล
$db = 'user_management'; // ชื่อฐานข้อมูล
$user = 'root'; // ชื่อผู้ใช้ฐานข้อมูล
$pass = ''; // รหัสผ่าน 

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "เชื่อมต่อฐานข้อมูลสำเร็จ"; // ใช้สำหรับทดสอบ
} catch (PDOException $e) {
    echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
}
?>
