<?php
session_start();

header('Content-Type: application/json');

// ตรวจสอบสิทธิ์
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

try {
    // การเชื่อมต่อฐานข้อมูล
    $conn = new PDO("mysql:host=localhost;dbname=recipe_database;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();

    // รับข้อมูลจาก JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $recipe_id = isset($input['recipe_id']) ? intval($input['recipe_id']) : 0;
    $reason = isset($input['reason']) ? trim($input['reason']) : '';

    if ($recipe_id <= 0 || empty($reason)) {
        throw new Exception("Invalid recipe ID or reason not provided");
    }

    // ดึงข้อมูล recipe, recipe_owner และ reporter_id
    $stmt = $conn->prepare("
        SELECT r.user_id AS recipe_owner, r.recipe_name, rep.user_id AS reporter_id
        FROM recipe r
        LEFT JOIN reports rep ON r.id = rep.recipe_id
        WHERE r.id = :recipe_id
    ");
    $stmt->execute([':recipe_id' => $recipe_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        throw new Exception('Recipe not found');
    }

    $recipe_owner = $result['recipe_owner'];
    $recipe_name = $result['recipe_name'];
    $reporter_id = $result['reporter_id'];

    // เพิ่มแจ้งเตือนให้เจ้าของสูตร
    $stmtInsertOwner = $conn->prepare("
        INSERT INTO notifications (user_id, recipe_name, message, created_at)
        VALUES (:user_id, :recipe_name, :message, NOW())
    ");
    $stmtInsertOwner->execute([
        ':user_id' => $recipe_owner,
        ':recipe_name' => $recipe_name,
        ':message' => "สูตร '{$recipe_name}' ของคุณถูกลบด้วยเหตุผล: {$reason}"
    ]);

    // เพิ่มแจ้งเตือนให้ผู้รายงาน (ถ้ามี)
    if ($reporter_id) {
        $stmtInsertReporter = $conn->prepare("
            INSERT INTO notifications (user_id, recipe_name, message, created_at)
            VALUES (:user_id, :recipe_name, :message, NOW())
        ");
        $stmtInsertReporter->execute([
            ':user_id' => $reporter_id,
            ':recipe_name' => $recipe_name,
            ':message' => "สูตร '{$recipe_name}' ที่คุณรายงานได้รับการตรวจสอบและถูกลบโดยแอดมิน"
        ]);
    }

    // ลบข้อมูลที่เกี่ยวข้อง
    $conn->prepare("DELETE FROM comments WHERE recipe_id = :recipe_id")->execute([':recipe_id' => $recipe_id]);
    $conn->prepare("DELETE FROM reports WHERE recipe_id = :recipe_id")->execute([':recipe_id' => $recipe_id]);

    // ลบสูตรอาหาร
    $stmtDeleteRecipe = $conn->prepare("DELETE FROM recipe WHERE id = :recipe_id");
    $stmtDeleteRecipe->execute([':recipe_id' => $recipe_id]);

    if ($stmtDeleteRecipe->rowCount() === 0) {
        throw new Exception("Recipe not found or could not be deleted");
    }

    // ยืนยัน transaction
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'ลบสูตรสำเร็จและส่งการแจ้งเตือนเรียบร้อยแล้ว']);

} catch (Exception $e) {
    // Rollback transaction หากเกิดข้อผิดพลาด
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    // บันทึกข้อผิดพลาดใน log และส่งกลับไปยัง client
    error_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
