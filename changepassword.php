<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'user_management');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'การเชื่อมต่อฐานข้อมูลล้มเหลว: ' . $conn->connect_error]));
}

// ตรวจสอบ session ผู้ใช้
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'ไม่มีสิทธิ์เข้าถึง']);
    exit;
}

$userId = $_SESSION['user_id'];

// ตรวจสอบว่ามีการส่งคำขอ GET หรือ POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // รับข้อมูลผู้ใช้
    $sql = "SELECT username, email, profile_image FROM users WHERE id = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        echo json_encode(['success' => true, 'user' => $userData]);
    } else {
        echo json_encode(['success' => false, 'error' => 'ไม่พบข้อมูลผู้ใช้']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // เปลี่ยนรหัสผ่าน
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['success' => false, 'error' => 'รหัสผ่านไม่ตรงกัน']);
        exit;
    }

    // เข้ารหัสรหัสผ่านใหม่และอัปเดต
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = '$hashedPassword' WHERE id = $userId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'บันทึกรหัสผ่านใหม่สำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'error' => 'อัปเดตรหัสผ่านล้มเหลว: ' . $conn->error]);
    }
}

$conn->close();
?>
