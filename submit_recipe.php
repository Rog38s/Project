<?php
header('Content-Type: application/json'); // กำหนดประเภทเนื้อหาเป็น JSON
error_reporting(0); // ปิดการแสดงข้อผิดพลาด
ini_set('display_errors', 0);

// ตรวจสอบว่าเป็นการร้องขอแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $recipeName = $_POST['recipe_name'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $source = $_POST['source'];
    $category = $_POST['food_category'];  // ประเภทของอาหาร (อาหารคาว หรือ ของหวาน)

    // ตรวจสอบว่าค่าที่ได้รับมาจากฟอร์มมีค่าหรือไม่
    if (empty($recipeName) || empty($ingredients) || empty($steps) || empty($category)) {
        echo json_encode(["success" => false, "message" => "กรุณากรอกข้อมูลให้ครบถ้วน"]);
        exit();
    }

    // ตั้งค่าให้ source เป็นชื่อผู้ใช้หากปล่อยว่างไว้
    session_start();
    if (empty($source)) {
        $source = $_SESSION['username'] ?? 'unknown_user'; // ใช้ 'unknown_user' ถ้า session ไม่มีค่า username
    }

    // ตรวจสอบการเลือกประเภทอาหารและกำหนดโฟลเดอร์เก็บไฟล์
    $targetDir = "";
    if ($category == "อาหารคาว") {
        $targetDir = "maindish_recipe/"; // โฟลเดอร์สำหรับอาหารคาว
    } elseif ($category == "ของหวาน") {
        $targetDir = "dessert_recipe/"; // โฟลเดอร์สำหรับของหวาน
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

        // ตรวจสอบขนาดไฟล์ (ถ้าไฟล์ใหญ่เกินไป)
        if ($_FILES["file-upload"]["size"] > 10000000) {  // 10MB
            echo json_encode(["success" => false, "message" => "ไฟล์มีขนาดใหญ่เกินไป"]);
            exit();
        }

        // ตรวจสอบไฟล์ที่อัปโหลด
        if (is_uploaded_file($_FILES["file-upload"]["tmp_name"])) {
            // ย้ายไฟล์ไปยังโฟลเดอร์ที่เลือก
            if (move_uploaded_file($_FILES["file-upload"]["tmp_name"], $targetFilePath)) {
                // อัปโหลดไฟล์สำเร็จ
            } else {
                echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการอัปโหลดไฟล์"]);
                exit();
            }
        } else {
            echo json_encode(["success" => false, "message" => "ไฟล์ที่อัปโหลดไม่ถูกต้อง"]);
            exit();
        }
    } else {
        echo json_encode(["success" => false, "message" => "กรุณาเลือกไฟล์เพื่ออัปโหลด"]);
        exit();
    }

    // เพิ่มการบันทึกข้อมูลลงในฐานข้อมูล
    try {
        // ตั้งค่าการเชื่อมต่อฐานข้อมูล
        $pdo = new PDO('mysql:host=localhost;dbname=recipe_database', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // เตรียมคำสั่ง SQL
        $stmt = $pdo->prepare("INSERT INTO recipe (recipe_name, ingredients, steps, source, food_category, image_path) VALUES (:recipe_name, :ingredients, :steps, :source, :food_category, :image_path)");

        // ผูกค่า parameter
        $stmt->bindParam(':recipe_name', $recipeName);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':steps', $steps);
        $stmt->bindParam(':source', $source);
        $stmt->bindParam(':food_category', $category);
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
