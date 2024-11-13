<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer\Exception.php';
require 'PHPMailer\PHPMailer.php';
require 'PHPMailer\SMTP.php';

// ตรวจสอบว่าได้รับอีเมลจากผู้ใช้หรือไม่
if (isset($_POST['email'])) {
    $user_email = $_POST['email'];

    // เชื่อมต่อกับฐานข้อมูลเพื่อตรวจสอบอีเมล
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=recipe_database', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // สร้าง token สำหรับการรีเซ็ตรหัสผ่าน
        $reset_token = bin2hex(random_bytes(16));
        $reset_link = "http://localhost/project1/reset_password.php?token=$reset_token";

        // บันทึก token ลงในฐานข้อมูล
        $stmt = $pdo->prepare("UPDATE users SET reset_token = :reset_token WHERE email = :email");
        $stmt->execute([':reset_token' => $reset_token, ':email' => $user_email]);

        // ส่งอีเมลรีเซ็ตรหัสผ่าน
        $mail = new PHPMailer(true);

        try {
            // ตั้งค่า SMTP ของ Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com';
            $mail->Password = 'your-app-password'; // ใช้ App password แทนรหัสผ่านจริง
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // ตั้งค่าอีเมลผู้ส่งและผู้รับ
            $mail->setFrom('thai_food_and_dessert@gmail.com', 'สำรับคาว-หวานของไทย: เว็บไซต์เกี่ยวกับการปรุงและสูตรการปรุง');
            $mail->addAddress($user_email);

            // เนื้อหาของอีเมล
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = 'คลิกลิงก์นี้เพื่อรีเซ็ตรหัสผ่านของคุณ: <a href="' . $reset_link . '">' . $reset_link . '</a>';

            $mail->send();
            echo 'ส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมลของคุณแล้ว';
        } catch (Exception $e) {
            echo 'ไม่สามารถส่งอีเมลได้. ข้อผิดพลาด: ', $mail->ErrorInfo;
        }

    } catch (PDOException $e) {
        echo 'ไม่สามารถบันทึกข้อมูลได้. ข้อผิดพลาด: ' . $e->getMessage();
    }

} else {
    echo 'กรุณากรอกอีเมล';
}
?>
