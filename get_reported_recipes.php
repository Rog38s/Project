<?php
// เชื่อมต่อฐานข้อมูล
$dsn = "mysql:host=localhost;dbname=recipe_database;charset=utf8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ดึงข้อมูลสูตรอาหารที่ถูกรายงาน
    $stmt = $pdo->prepare("
        SELECT recipe.id, recipe.recipe_name, recipe.image_path, recipe.rating, recipe.source, recipe.created_at, reports.report_text 
        FROM reports 
        JOIN recipe ON reports.recipe_id = recipe.id
    ");
    $stmt->execute();

    // เก็บข้อมูลในรูปแบบ JSON
    $reportedRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reportedRecipes);

} catch (PDOException $e) {
    echo json_encode(['error' => 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage()]);
}
?>
