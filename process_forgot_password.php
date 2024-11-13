<?php
// เชื่อมต่อกับฐานข้อมูล
$conn = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // ตรวจสอบว่ามีอีเมลนี้ในฐานข้อมูลหรือไม่
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // สร้างโทเคนและลิงก์รีเซ็ตรหัสผ่าน
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();

        // ส่งอีเมลพร้อมลิงก์รีเซ็ตรหัสผ่าน
        $reset_link = "http://yourwebsite.com/reset_password.php?token=$token";
        mail($email, "รีเซ็ตรหัสผ่าน", "คลิกลิงก์นี้เพื่อรีเซ็ตรหัสผ่านของคุณ: $reset_link");

        echo "ส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมลของคุณแล้ว";
    } else {
        echo "ไม่พบบัญชีที่ใช้อีเมลนี้";
    }
}
?>
