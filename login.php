<?php
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
            echo "เข้าสู่ระบบสำเร็จ"; // แจ้งเตือนเมื่อเข้าสู่ระบบสำเร็จ
            // คุณสามารถเปลี่ยนไปยังหน้าหลักที่ต้องการได้ที่นี่
            header("Location: main.html");
            exit();
        } else {
            echo "รหัสผ่านไม่ถูกต้อง"; // แจ้งเตือนเมื่อรหัสผ่านไม่ถูกต้อง
        }
    } else {
        echo "ไม่พบผู้ใช้"; // แจ้งเตือนเมื่อไม่พบผู้ใช้
    }
}
?>
