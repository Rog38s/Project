<?php
header('Content-Type: application/json'); // กำหนดประเภทเนื้อหาเป็น JSON

// ตรวจสอบว่าเป็นการร้องขอแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $recipeName = $_POST['recipe-name'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $source = $_POST['source'];
    $category = $_POST['food-category'];  // ประเภทของอาหาร (อาหารคาว หรือ ของหวาน)

    // ตั้งค่าให้ source เป็นชื่อผู้ใช้หากปล่อยว่างไว้
    if (empty($source)) {
        // สมมติว่าเรามี session ที่เก็บชื่อผู้ใช้ไว้แล้ว
        session_start();
        $source = $_SESSION['username'] ?? 'unknown_user'; // ใช้ 'unknown_user' ถ้า session ไม่มีค่า username
    }

    // ตรวจสอบการเลือกประเภทอาหารและกำหนดโฟลเดอร์เก็บไฟล์
    $targetDir = "";
    if ($category == "อาหารคาว") {
        $targetDir = "maindish_recipe/"; // แก้ไขชื่อโฟลเดอร์ให้ถูกต้อง
    } elseif ($category == "ของหวาน") {
        $targetDir = "dessert_recipe/"; // แก้ไขชื่อโฟลเดอร์ให้ถูกต้อง
    } else {
        echo json_encode(["success" => false, "message" => "กรุณาเลือกประเภทอาหารที่ถูกต้อง"]);
        exit();
    }

    // ตรวจสอบและสร้างโฟลเดอร์หากยังไม่มี
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);  // สร้างโฟลเดอร์พร้อมกำหนดสิทธิ์
    }

    // ตรวจสอบการอัปโหลดไฟล์
    if (isset($_FILES["file-upload"]) && $_FILES["file-upload"]["error"] == 0) {
        $fileName = basename($_FILES["file-upload"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        // ตรวจสอบและย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ที่เลือก
        if (move_uploaded_file($_FILES["file-upload"]["tmp_name"], $targetFilePath)) {
            // อัปโหลดไฟล์สำเร็จ
        } else {
            echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการอัปโหลดไฟล์"]);
            exit();
        }
    } else {
        echo json_encode(["success" => false, "message" => "กรุณาเลือกไฟล์เพื่ออัปโหลด"]);
        exit();
    }

    // เพิ่มการบันทึกข้อมูลลงในฐานข้อมูล (หากต้องการ)
    try {
        // ตั้งค่าการเชื่อมต่อฐานข้อมูล
        $pdo = new PDO('mysql:host=localhost;dbname=user_management', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // เตรียมคำสั่ง SQL
        $stmt = $pdo->prepare("INSERT INTO recipes (name, ingredients, steps, source, category, image_path) VALUES (:name, :ingredients, :steps, :source, :category, :image_path)");

        // ผูกค่า parameter
        $stmt->bindParam(':name', $recipeName);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':steps', $steps);
        $stmt->bindParam(':source', $source);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':image_path', $targetFilePath);

        // บันทึกข้อมูลลงฐานข้อมูล
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "บันทึกสูตรอาหารสำเร็จ!"]);
        } else {
            echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการบันทึกสูตรอาหาร"]);
        }

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage()]);
    }

} else {
    echo json_encode(["success" => false, "message" => "การร้องขอไม่ถูกต้อง"]);
}
?>
