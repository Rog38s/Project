<?php
session_start();
header('Content-Type: application/json');

// ตรวจสอบว่าเป็นแอดมินหรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=recipe_database;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// รับข้อมูล JSON และแปลงค่า
$input = json_decode(file_get_contents('php://input'), true);
$recipe_id = isset($input['recipe_id']) ? intval($input['recipe_id']) : 0;

if ($recipe_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid recipe_id']);
    exit;
}

try {
    // เริ่ม transaction
    $pdo->beginTransaction();

    // ตรวจสอบว่าสูตรนี้มีอยู่ในฐานข้อมูลหรือไม่
    $stmt = $pdo->prepare("
        SELECT r.recipe_name, r.user_id AS owner_id, rep.user_id AS reporter_id
        FROM recipe r
        LEFT JOIN reports rep ON r.id = rep.recipe_id
        WHERE r.id = :recipe_id
    ");
    $stmt->execute([':recipe_id' => $recipe_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo json_encode(['success' => false, 'error' => 'ไม่พบสูตรอาหารในระบบ']);
        exit;
    }

    $reporter_id = $result['reporter_id'];
    $recipe_name = $result['recipe_name'];

    if ($reporter_id) {
        // ลบรายงาน
        $stmt = $pdo->prepare("DELETE FROM reports WHERE recipe_id = :recipe_id");
        $stmt->execute([':recipe_id' => $recipe_id]);

        // เพิ่มการแจ้งเตือนไปยังผู้รายงาน
        $notificationStmt = $pdo->prepare("
            INSERT INTO notifications (user_id, recipe_name, message, created_at) 
            VALUES (:user_id, :recipe_name, :message, NOW())
        ");
        $notificationStmt->execute([
            ':user_id' => $reporter_id,
            ':recipe_name' => $recipe_name,
            ':message' => "การรายงานสูตร '{$recipe_name}' ของคุณได้รับการตรวจสอบแล้ว"
        ]);
    }

    // ยืนยันการเปลี่ยนแปลง
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'รายงานถูกลบเรียบร้อยแล้ว',
        'recipe_id' => $recipe_id,
        'recipe_name' => $recipe_name
    ]);
} catch (PDOException $e) {
    // Rollback transaction
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
