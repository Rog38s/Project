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
        echo "รหัสผ่านไม่ตรงกัน"; // แจ้งเตือนหากรหัสผ่านไม่ตรงกัน
        exit;
    }

    // แฮชรหัสผ่านก่อนเก็บลงฐานข้อมูล
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่า username มีอยู่ในฐานข้อมูลแล้วหรือไม่
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้ไปแล้ว"; // แจ้งเตือนเมื่อ username หรือ email มีอยู่แล้ว
    } else {
        // เก็บข้อมูลผู้ใช้ใหม่ในฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            echo "ลงทะเบียนสำเร็จ"; // แจ้งเตือนเมื่อสมัครสมาชิกสำเร็จ
            header("Location: main.html"); // นำไปยังหน้าอื่น
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการลงทะเบียน";
        }
    }
}
?>
