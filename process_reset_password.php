<?php
$conn = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน กรุณาลองใหม่.'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    try {
        // ตรวจสอบโทเคนและวันหมดอายุ
        $stmt = $conn->prepare("SELECT id, reset_token_expiry FROM users WHERE reset_token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // ตรวจสอบว่าโทเคนหมดอายุหรือไม่
            if (strtotime($user['reset_token_expiry']) > time()) {
                // อัปเดตรหัสผ่านใหม่และลบโทเคนรีเซ็ต
                $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id");
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':id', $user['id']);
                $stmt->execute();

                echo "<script>alert('รีเซ็ตรหัสผ่านสำเร็จ! กำลังนำทางไปหน้าเข้าสู่ระบบ...'); window.location.href = 'login.html';</script>";
                exit();
            } else {
                echo "<script>alert('ลิงก์รีเซ็ตรหัสผ่านหมดอายุ กรุณาขอใหม่.'); window.location.href = 'forgot_password.html';</script>";
            }
        } else {
            echo "<script>alert('โทเคนไม่ถูกต้อง กรุณาลองใหม่.'); window.location.href = 'forgot_password.html';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "'); window.location.href = 'forgot_password.html';</script>";
    }
}
?>
