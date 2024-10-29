<?php
session_start(); // เริ่มต้นเซสชัน
include 'db_connection.php'; // เชื่อมต่อไฟล์ฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ตรวจสอบข้อมูลผู้ใช้ในฐานข้อมูล
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    // ตรวจสอบว่าพบผู้ใช้หรือไม่
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['password'])) {
            // ตั้งค่าเซสชันเมื่อล็อกอินสำเร็จ
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // เปลี่ยนไปยังหน้าหลักที่ต้องการ
            header("Location: main.html");
            exit();
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง');</script>"; // แจ้งเตือนเมื่อรหัสผ่านไม่ถูกต้อง
        }
    } else {
        echo "<script>alert('ไม่พบผู้ใช้');</script>"; // แจ้งเตือนเมื่อไม่พบผู้ใช้
    }
}
?>
