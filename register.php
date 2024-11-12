<?php
include 'db_connection.php'; // เชื่อมต่อไฟล์ฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่ารหัสผ่านและยืนยันรหัสผ่านตรงกันหรือไม่
    if ($password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน');</script>"; // แจ้งเตือนหากรหัสผ่านไม่ตรงกัน
        exit;
    }

    // แฮชรหัสผ่านก่อนเก็บลงฐานข้อมูล
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่า username หรือ email มีอยู่ในฐานข้อมูลแล้วหรือไม่
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้ไปแล้ว');</script>"; // แจ้งเตือนเมื่อ username หรือ email มีอยู่แล้ว
    } else {
        // กำหนด role เป็น 'user' สำหรับผู้ใช้ใหม่
        $role = 'user';

        // เก็บข้อมูลผู้ใช้ใหม่ในฐานข้อมูลพร้อม role
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            echo "<script>alert('ลงทะเบียนสำเร็จ');</script>"; // แจ้งเตือนเมื่อสมัครสมาชิกสำเร็จ
            echo "<script>window.location.href = 'main.html';</script>"; // นำไปยังหน้าอื่น
            exit();
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการลงทะเบียน');</script>"; // แจ้งเตือนหากเกิดข้อผิดพลาด
        }
    }
}
?>
