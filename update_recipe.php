<?php
// update_recipe.php
session_start();

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST และเป็น JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูล JSON และแปลงเป็น PHP array
    $data = json_decode(file_get_contents('php://input'), true);
    
    // ตรวจสอบว่ามีข้อมูลที่จำเป็นครบถ้วน
    if (!isset($data['recipe_id']) || !isset($data['recipe_name']) || 
        !isset($data['ingredients']) || !isset($data['steps'])) {
        echo json_encode(['success' => false, 'error' => 'ข้อมูลไม่ครบถ้วน']);
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

        // ตรวจสอบว่าผู้ใช้เป็นเจ้าของสูตรนี้
        $stmt = $pdo->prepare("SELECT user_id FROM recipe WHERE id = ?");
        $stmt->execute([$data['recipe_id']]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'คุณไม่มีสิทธิ์แก้ไขสูตรนี้']);
            exit;
        }

        // อัปเดตข้อมูลสูตรอาหาร
        $stmt = $pdo->prepare("
            UPDATE recipe 
            SET recipe_name = ?, 
                ingredients = ?, 
                steps = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ? AND user_id = ?
        ");

        $success = $stmt->execute([
            $data['recipe_name'],
            $data['ingredients'],
            $data['steps'],
            $data['recipe_id'],
            $_SESSION['user_id']
        ]);

        echo json_encode(['success' => $success]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request method']);
?>
