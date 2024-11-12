<?php
header('Content-Type: application/json'); // กำหนดประเภทเนื้อหาเป็น JSON
error_reporting(E_ALL); // แสดงข้อผิดพลาดทั้งหมด
ini_set('display_errors', 1); // แสดงข้อผิดพลาด

// ตรวจสอบว่าเป็นการร้องขอแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // เริ่ม session เพื่อดึงข้อมูล user_id
    session_start();

    // ตรวจสอบว่ามีการเข้าสู่ระบบหรือไม่
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "กรุณาเข้าสู่ระบบก่อน"]);
        exit();
    }

    // รับค่าจากฟอร์มและ user_id จาก session
    $recipeName = $_POST['recipe_name'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $source = $_POST['source'];
    $category = $_POST['food_category'];
    $user_id = $_SESSION['user_id']; // ดึง user_id จาก session

    // ตรวจสอบว่าค่าที่ได้รับมาจากฟอร์มมีค่าหรือไม่
    if (empty($recipeName) || empty($ingredients) || empty($steps) || empty($category)) {
        echo json_encode(["success" => false, "message" => "กรุณากรอกข้อมูลให้ครบถ้วน"]);
        exit();
    }

    // ตั้งค่าให้ source เป็นชื่อผู้ใช้หากปล่อยว่างไว้
    if (empty($source)) {
        $source = $_SESSION['username'] ?? 'unknown_user';
    }

    // ตรวจสอบการเลือกประเภทอาหารและกำหนดโฟลเดอร์เก็บไฟล์
    $targetDir = ($category == "อาหารคาว") ? "maindish_recipe/" : "dessert_recipe/";

    // ตรวจสอบและสร้างโฟลเดอร์หากยังไม่มี
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // ตรวจสอบการอัปโหลดไฟล์
    if (isset($_FILES["file-upload"]) && $_FILES["file-upload"]["error"] == 0) {
        $fileName = basename($_FILES["file-upload"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if ($_FILES["file-upload"]["size"] > 10000000) { // 10MB
            echo json_encode(["success" => false, "message" => "ไฟล์มีขนาดใหญ่เกินไป"]);
            exit();
        }

        if (is_uploaded_file($_FILES["file-upload"]["tmp_name"])) {
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
        $pdo = new PDO('mysql:host=localhost;dbname=recipe_database', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // เตรียมคำสั่ง SQL โดยเพิ่มฟิลด์ user_id
        $stmt = $pdo->prepare("INSERT INTO recipe (recipe_name, ingredients, steps, source, food_category, image_path, created_at, user_id) VALUES (:recipe_name, :ingredients, :steps, :source, :food_category, :image_path, NOW(), :user_id)");

        // กำหนดค่าพารามิเตอร์
        $stmt->bindParam(':recipe_name', $recipeName);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':steps', $steps);
        $stmt->bindParam(':source', $source);
        $stmt->bindParam(':food_category', $category);
        $stmt->bindParam(':image_path', $targetFilePath);
        $stmt->bindParam(':user_id', $user_id);

        // บันทึกข้อมูลลงฐานข้อมูล
        $stmt->execute();

        echo json_encode(["success" => true, "message" => "บันทึกข้อมูลสำเร็จ"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "การร้องขอไม่ถูกต้อง"]);
}
?>
