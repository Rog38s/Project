<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to file
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    error_log("No user_id in session");
    echo json_encode(['error' => 'กรุณาเข้าสู่ระบบก่อนแสดงความคิดเห็น']);
    exit;
}

// Debug: Log POST data
error_log("POST data: " . print_r($_POST, true));

// ตรวจสอบข้อมูลที่ส่งมา
if (!isset($_POST['recipe_id']) || !isset($_POST['rating']) || !isset($_POST['comment'])) {
    error_log("Missing required fields");
    echo json_encode(['error' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

$recipe_id = intval($_POST['recipe_id']);
$user_id = $_SESSION['user_id'];
$rating = intval($_POST['rating']);
$comment = trim($_POST['comment']);

// Debug: Log processed data
error_log("Processed data: recipe_id=$recipe_id, user_id=$user_id, rating=$rating, comment=$comment");

try {
    // เชื่อมต่อฐานข้อมูลโดยตรง
    $pdo = new PDO(
        "mysql:host=localhost;dbname=recipe_database;charset=utf8",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // ตรวจสอบว่า recipe_id มีอยู่จริง
    $check_recipe = $pdo->prepare("SELECT id FROM recipe WHERE id = ?");
    $check_recipe->execute([$recipe_id]);
    if (!$check_recipe->fetch()) {
        throw new Exception("ไม่พบสูตรอาหารที่ระบุ");
    }

    // เริ่ม transaction
    $pdo->beginTransaction();

    // เพิ่มความคิดเห็นใหม่
    $stmt = $pdo->prepare("INSERT INTO comments (recipe_id, user_id, rating, comment_text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$recipe_id, $user_id, $rating, $comment]);

    // คำนวณคะแนนเฉลี่ยใหม่
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM comments WHERE recipe_id = ?");
    $stmt->execute([$recipe_id]);
    $result = $stmt->fetch();
    $avg_rating = round($result['avg_rating'], 1);

    // อัพเดทคะแนนในตาราง recipe
    $stmt = $pdo->prepare("UPDATE recipe SET rating = ? WHERE id = ?");
    $stmt->execute([$avg_rating, $recipe_id]);

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'avg_rating' => $avg_rating
    ]);

} catch (Exception $e) {
    // Rollback transaction ในกรณีที่เกิดข้อผิดพลาด
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Error in save_comment.php: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>