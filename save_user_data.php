<?php
session_start(); // เริ่มต้น session

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'user_management');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]));
}

// ตรวจสอบ session ผู้ใช้
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$userId = $_SESSION['user_id']; // รับ user_id จาก session

// รับข้อมูลจากฟอร์ม
$name = $conn->real_escape_string($_POST['name']);
$phone = $conn->real_escape_string($_POST['phone']);
$gender = $conn->real_escape_string($_POST['gender']);
$age = (int)$_POST['age']; 

// ตรวจสอบว่ามีไฟล์รูปภาพหรือไม่
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['profile_image']['name']);

    // ย้ายไฟล์ไปที่โฟลเดอร์ uploads
    if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
        echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
        exit;
    }

    // เพิ่มส่วนอัปเดตรูปภาพหากมีการอัปโหลดไฟล์
    $sql = "UPDATE users SET username='$name', phone='$phone', gender='$gender', age=$age, profile_image='$target_file' WHERE id=$userId";
} else {
    // หากไม่มีการอัปโหลดไฟล์รูปภาพ ให้ข้ามการอัปเดตรูปภาพ
    $sql = "UPDATE users SET username='$name', phone='$phone', gender='$gender', age=$age WHERE id=$userId";
}

// รันคำสั่ง SQL
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $conn->error]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
