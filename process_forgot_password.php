<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer\Exception.php';
require 'PHPMailer\PHPMailer.php';
require 'PHPMailer\SMTP.php';

if (isset($_POST['email'])) {
    $user_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$user_email) {
        echo "<script>alert('กรุณากรอกอีเมลที่ถูกต้อง'); window.history.back();</script>";
        exit();
    }

    try {
        // เชื่อมต่อกับฐานข้อมูล
        $pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ตรวจสอบว่าอีเมลมีอยู่ในระบบหรือไม่
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $user_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "<script>alert('หากอีเมลนี้อยู่ในระบบ เราจะส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมลของคุณ'); window.location.href = 'forgot_password.html';</script>";
            exit();
        }

        // สร้าง token และบันทึกเวลาหมดอายุ
        $reset_token = bin2hex(random_bytes(16));
        $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes')); // ตั้งเวลาหมดอายุเป็น 15 นาที
        $reset_link = "http://localhost/project1/confirm_password.html?token=$reset_token";

        // บันทึก token และเวลาหมดอายุลงในฐานข้อมูล
        $stmt = $pdo->prepare("UPDATE users SET reset_token = :reset_token, reset_token_expiry = :expires_at WHERE email = :email");
        $stmt->execute([
            ':reset_token' => $reset_token,
            ':expires_at' => $expires_at,
            ':email' => $user_email
        ]);

        // ส่งอีเมลรีเซ็ตรหัสผ่าน
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tanatdith2545@gmail.com'; // เปลี่ยนเป็นอีเมลจริงของคุณ
            $mail->Password = 'ifvpfmyqmhvndslq'; // เปลี่ยนเป็น App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8'; // ตั้งค่าการเข้ารหัสข้อความ
            $mail->setFrom('tanatdith2545@gmail.com', 'สำรับคาว-หวานของไทย');
            $mail->addAddress($user_email);

            $mail->isHTML(true);
            $mail->Subject = 'รีเซ็ตรหัสผ่าน - สำรับคาว-หวานของไทย';
            $mail->Body = "
                <p>เรียนคุณผู้ใช้งาน,</p>
                <p>เราพบคำขอสำหรับการรีเซ็ตรหัสผ่านของคุณ หากเป็นคำขอของคุณ กรุณาคลิกลิงก์ด้านล่างเพื่อดำเนินการรีเซ็ตรหัสผ่าน:</p>
                <p><a href='$reset_link'>$reset_link</a></p>
                <p><strong>ลิงก์นี้จะหมดอายุใน 15 นาที</strong></p>
                <p>หากคุณไม่ได้เป็นผู้ส่งคำขอนี้ กรุณาเพิกเฉยต่ออีเมลนี้</p>
                <br>
                <p>ด้วยความนับถือ,</p>
                <p>ทีมงานสำรับคาว-หวานของไทย</p>
            ";

            $mail->send();
            echo "<script>alert('หากอีเมลนี้อยู่ในระบบ เราจะส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมลของคุณ'); window.location.href = 'forgot_password.html';</script>";
        } catch (Exception $e) {
            error_log('PHPMailer Error: ' . $mail->ErrorInfo);
            echo "<script>alert('ไม่สามารถส่งอีเมลได้ในขณะนี้. กรุณาลองใหม่อีกครั้ง.'); window.location.href = 'forgot_password.html';</script>";
        }
    } catch (PDOException $e) {
        error_log('Database Error: ' . $e->getMessage());
        echo "<script>alert('เกิดข้อผิดพลาดกับฐานข้อมูล: " . $e->getMessage() . "'); window.location.href = 'forgot_password.html';</script>";
    }
} else {
    echo "<script>alert('กรุณากรอกอีเมล'); window.location.href = 'forgot_password.html';</script>";
}
?>
