<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รีเซ็ตรหัสผ่าน</title>
</head>
<body>
    <h2>รีเซ็ตรหัสผ่าน</h2>
    <form action="process_reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        <label for="password">รหัสผ่านใหม่:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">รีเซ็ตรหัสผ่าน</button>
    </form>
</body>
</html>
