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
$birthDate = $conn->real_escape_string($_POST['birth_date']); // รับวันเดือนปีเกิด (วัน/เดือน/ปี)

// แปลงวันที่จาก 'วัน/เดือน/ปี' เป็น 'ปี-เดือน-วัน' สำหรับฐานข้อมูล
$birthDateParts = explode('/', $birthDate);
if (count($birthDateParts) === 3) {
    $birthDate = "{$birthDateParts[2]}-{$birthDateParts[1]}-{$birthDateParts[0]}";
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid date format']);
    exit;
}

// ตรวจสอบว่ามีไฟล์รูปภาพหรือไม่
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    // ดึงรูปภาพเดิมจากฐานข้อมูล
    $oldImageQuery = $conn->query("SELECT profile_image FROM users WHERE id = $userId");
    $oldImageData = $oldImageQuery->fetch_assoc();
    $oldImagePath = $oldImageData['profile_image'];

    $target_dir = "uploads/";
    $file_name = basename($_FILES['profile_image']['name']);
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    // ตรวจสอบชนิดของไฟล์
    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(['success' => false, 'error' => 'Invalid file type']);
        exit;
    }

    // สร้างชื่อไฟล์ใหม่เพื่อป้องกันการเขียนทับ
    $new_file_name = $target_dir . uniqid("profile_", true) . '.' . $file_extension;

    // ย้ายไฟล์ไปที่โฟลเดอร์ uploads
    if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $new_file_name)) {
        echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
        exit;
    }

    // ลบรูปภาพเดิมถ้ามีและไม่ใช่ค่าเริ่มต้น
    if (!empty($oldImagePath) && file_exists($oldImagePath) && strpos($oldImagePath, 'usericon.png') === false) {
        unlink($oldImagePath);
    }

    // อัปเดตข้อมูลรวมถึงรูปภาพ
    $sql = "UPDATE users SET username='$name', phone='$phone', gender='$gender', birth_date='$birthDate', profile_image='$new_file_name' WHERE id=$userId";
} else {
    // หากไม่มีการอัปโหลดไฟล์รูปภาพ ให้ข้ามการอัปเดตรูปภาพ
    $sql = "UPDATE users SET username='$name', phone='$phone', gender='$gender', birth_date='$birthDate' WHERE id=$userId";
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
