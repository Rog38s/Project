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
            $_SESSION['role'] = $user['role']; // เพิ่ม role เพื่อใช้ตรวจสอบสิทธิ์

            // ตรวจสอบสิทธิ์และเปลี่ยนหน้า
            if ($user['role'] === 'admin') {
                header("Location: main.html"); // เปลี่ยนหน้าไปยังหน้าของแอดมิน
            } else {
                header("Location: main.html"); // เปลี่ยนไปยังหน้าผู้ใช้ทั่วไป
            }
            exit();
        } else {
            // รหัสผ่านไม่ถูกต้อง
            echo "<script>
                    alert('รหัสผ่านไม่ถูกต้อง');
                    window.location.href = 'login.html';
                  </script>";
        }
    } else {
        // ไม่พบผู้ใช้
        echo "<script>
                alert('ไม่พบผู้ใช้');
                window.location.href = 'login.html';
              </script>";
    }
}
?>
