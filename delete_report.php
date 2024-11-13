<?php
session_start();
header('Content-Type: application/json');

// Debug: แสดงข้อมูลที่ได้รับ
error_log('POST data: ' . print_r($_POST, true));

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=recipe_database', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// ตรวจสอบการรับค่า recipe_id
if (!isset($_POST['recipe_id'])) {
    echo json_encode(['error' => 'No recipe_id provided']);
    exit;
}

// แปลงค่าและตรวจสอบความถูกต้อง
$recipe_id = filter_var($_POST['recipe_id'], FILTER_SANITIZE_NUMBER_INT);

// Debug: แสดงค่า recipe_id หลังจาก sanitize
error_log('Sanitized recipe_id: ' . $recipe_id);

if ($recipe_id === '' || $recipe_id === false || $recipe_id === null) {
    echo json_encode(['error' => 'Invalid recipe_id format']);
    exit;
}

try {
    // เริ่ม transaction
    $pdo->beginTransaction();

    // ตรวจสอบว่ามีรายงานอยู่หรือไม่
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM reports WHERE recipe_id = :recipe_id");
    $checkStmt->bindValue(':recipe_id', $recipe_id, PDO::PARAM_INT);
    $checkStmt->execute();
    $exists = $checkStmt->fetchColumn();

    // Debug: แสดงผลการตรวจสอบ
    error_log('Report exists: ' . ($exists ? 'true' : 'false'));

    if ($exists) {
        // ลบรายงาน
        $stmt = $pdo->prepare("DELETE FROM reports WHERE recipe_id = :recipe_id");
        $stmt->bindValue(':recipe_id', $recipe_id, PDO::PARAM_INT);
        $success = $stmt->execute();

        if ($success) {
            $pdo->commit();
            echo json_encode([
                'success' => true,
                'message' => 'รายงานถูกลบเรียบร้อยแล้ว',
                'recipe_id' => $recipe_id // ส่งค่ากลับเพื่อตรวจสอบ
            ]);
        } else {
            $pdo->rollBack();
            echo json_encode(['error' => 'Failed to delete report']);
        }
    } else {
        $pdo->rollBack();
        echo json_encode([
            'error' => 'ไม่พบรายงานนี้ในระบบ',
            'recipe_id' => $recipe_id // ส่งค่ากลับเพื่อตรวจสอบ
        ]);
    }

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>