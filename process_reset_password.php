<?php
$conn = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ตรวจสอบโทเคนและวันหมดอายุ
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = :token AND reset_token_expiry > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // อัปเดตรหัสผ่านใหม่และลบโทเคนรีเซ็ต
        $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id");
        $stmt->bindParam(':password', $new_password);
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();

        echo "รีเซ็ตรหัสผ่านสำเร็จ!";
    } else {
        echo "ลิงก์รีเซ็ตรหัสผ่านไม่ถูกต้องหรือหมดอายุ";
    }
}
?>
