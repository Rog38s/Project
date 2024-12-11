<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีการส่งข้อมูล JSON หรือข้อมูลไฟล์
    if (empty($_FILES['image']) && empty($_POST['data'])) {
        echo json_encode(['success' => false, 'error' => 'ไม่มีข้อมูลที่ถูกส่งมา']);
        exit;
    }

    // การตั้งค่าการเชื่อมต่อฐานข้อมูล
    $db_host = 'localhost';
    $db_name = 'recipe_database';
    $db_user = 'root';
    $db_pass = '';

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ตรวจสอบว่าเป็นเจ้าของสูตรอาหาร
        $data = json_decode($_POST['data'], true);
        if (!isset($data['recipe_id'], $data['recipe_name'], $data['ingredients'], $data['steps'], $data['source'])) {
            echo json_encode(['success' => false, 'error' => 'ข้อมูลไม่ครบถ้วน']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT user_id FROM recipe WHERE id = ?");
        $stmt->execute([$data['recipe_id']]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'คุณไม่มีสิทธิ์แก้ไขสูตรนี้']);
            exit;
        }

        // จัดการอัปโหลดรูปภาพ
        $imagePath = $data['image_path']; // ค่าเริ่มต้นจาก Frontend
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/'; // โฟลเดอร์สำหรับเก็บรูปภาพ
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);

            // ตรวจสอบประเภทไฟล์
            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(['success' => false, 'error' => 'ประเภทไฟล์ไม่ถูกต้อง']);
                exit;
            }

            // สร้างชื่อไฟล์ใหม่เพื่อหลีกเลี่ยงชื่อซ้ำ
            $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
            $filePath = $uploadDir . $fileName;

            // ย้ายไฟล์ไปยังโฟลเดอร์เป้าหมาย
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $imagePath = $filePath; // อัปเดต Path ของรูปภาพ
            } else {
                echo json_encode(['success' => false, 'error' => 'ไม่สามารถบันทึกไฟล์ได้']);
                exit;
            }
        }

        // อัปเดตข้อมูลสูตรอาหารในฐานข้อมูล
        $stmt = $pdo->prepare("UPDATE recipe 
            SET recipe_name = ?, 
                ingredients = ?, 
                steps = ?, 
                source = ?, 
                image_path = ?, 
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ? AND user_id = ?");

        $success = $stmt->execute([
            $data['recipe_name'],
            $data['ingredients'],
            $data['steps'],
            $data['source'],
            $imagePath,
            $data['recipe_id'],
            $_SESSION['user_id']
        ]);

        if ($success) {
            echo json_encode(['success' => true, 'updated_at' => date('Y-m-d H:i:s')]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ไม่สามารถอัปเดตข้อมูลได้']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request method']);
?>
