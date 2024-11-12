<?php
// delete_recipe.php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // ตรวจสอบว่ามี recipe_id
    if (!isset($_GET['recipe_id'])) {
        echo json_encode(['success' => false, 'error' => 'ไม่พบ ID ของสูตรอาหาร']);
        exit;
    }

    $recipe_id = $_GET['recipe_id'];

    // การตั้งค่าการเชื่อมต่อฐานข้อมูล
    $db_host = 'localhost';
    $db_name = 'recipe_database';
    $db_user = 'root';
    $db_pass = '';

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ตรวจสอบว่าผู้ใช้เป็นเจ้าของสูตรนี้
        $stmt = $pdo->prepare("SELECT user_id, image_path FROM recipe WHERE id = ?");
        $stmt->execute([$recipe_id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'คุณไม่มีสิทธิ์ลบสูตรนี้']);
            exit;
        }

        // เริ่ม transaction
        $pdo->beginTransaction();

        // ลบความคิดเห็นที่เกี่ยวข้องกับสูตรนี้ก่อน
        $stmt = $pdo->prepare("DELETE FROM comments WHERE recipe_id = ?");
        $stmt->execute([$recipe_id]);

        // ลบสูตรอาหาร
        $stmt = $pdo->prepare("DELETE FROM recipe WHERE id = ? AND user_id = ?");
        $success = $stmt->execute([$recipe_id, $_SESSION['user_id']]);

        if ($success) {
            $pdo->commit();

            if ($recipe['image_path'] && file_exists($recipe['image_path'])) {
                unlink($recipe['image_path']);
            }
            echo json_encode(['success' => true]);
        } else {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => 'ไม่สามารถลบสูตรได้']);
        }

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request method']);
?>
