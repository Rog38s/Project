<?php
// เชื่อมต่อฐานข้อมูล
$dsn = 'mysql:host=localhost;dbname=recipe_database;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB_CONNECTION_ERROR']);
    exit();
}

// ตรวจสอบว่าเป็น POST และมีข้อมูลที่ต้องการ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id'], $_POST['feedback'])) {
    session_start();

    // ตรวจสอบสิทธิ์ผู้ใช้งาน
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'error' => 'INSUFFICIENT_PERMISSIONS']);
        exit();
    }

    $admin_id = $_SESSION['user_id'];
    $recipe_id = intval($_POST['recipe_id']);
    $feedback_text = trim($_POST['feedback']);

    // ตรวจสอบข้อมูล
    if ($recipe_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'INVALID_RECIPE_ID']);
        exit();
    }

    if (empty($feedback_text)) {
        echo json_encode(['success' => false, 'error' => 'FEEDBACK_EMPTY']);
        exit();
    }

    if (strlen($feedback_text) > 1000) {
        echo json_encode(['success' => false, 'error' => 'FEEDBACK_TOO_LONG']);
        exit();
    }

    try {
        // ตรวจสอบว่า recipe_id มีอยู่ในฐานข้อมูล และดึงข้อมูล recipe_name และ owner_id
        $stmt = $pdo->prepare('SELECT user_id, recipe_name FROM recipe WHERE id = :recipe_id');
        $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
        $stmt->execute();
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$recipe) {
            echo json_encode(['success' => false, 'error' => 'RECIPE_NOT_FOUND']);
            exit();
        }

        $owner_id = $recipe['user_id'];
        $recipe_name = $recipe['recipe_name'];

        // ตรวจสอบว่า admin_id มีอยู่ในฐานข้อมูลและเป็น admin
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM user_management.users WHERE id = :admin_id AND role = "admin"');
        $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            echo json_encode(['success' => false, 'error' => 'ADMIN_NOT_FOUND']);
            exit();
        }

        // บันทึกข้อความ Feedback ลงในตาราง notifications
        $stmt = $pdo->prepare(
            'INSERT INTO notifications (user_id, recipe_name, message, created_at)
             VALUES (:user_id, :recipe_name, :message, NOW())'
        );
        $stmt->bindParam(':user_id', $owner_id, PDO::PARAM_INT); // เจ้าของสูตร
        $stmt->bindParam(':recipe_name', $recipe_name, PDO::PARAM_STR);
        $stmt->bindParam(':message', $feedback_text, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'DB_INSERT_ERROR', 'details' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'INVALID_REQUEST']);
}
