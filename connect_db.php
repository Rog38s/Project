<?php
$email = $_POST['email'];
$password = $_POST['password'];

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "user";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);   

}

// เตรียมคำสั่ง SQL (Prepared Statements)
$stmt = $conn->prepare("INSERT INTO login (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $password);

// ถ่ายทอดคำสั่ง SQL
if ($stmt->execute()) {
    echo "สมัครสมาชิกสำเร็จ";
} else {
    echo "เกิดข้อผิดพลาดในการสมัครสมาชิก: " . $stmt->error;
}

// ปิดการเตรียมคำสั่งและการเชื่อมต่อ
$stmt->close();
$conn->close();
?>