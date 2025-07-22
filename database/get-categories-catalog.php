<?php
require_once '../database/connect.php'; // Затем используем BASE_PATH
if (isset($_POST['category_id'])) {
    $categoryId = (int)$_POST['category_id'];

    $stmt = $pdo->prepare("SELECT category_name FROM categories WHERE category_id = ?");
    $stmt->execute([$categoryId]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        echo htmlspecialchars($category['category_name']);
    } else {
        http_response_code(404);
        echo 'Категория не найдена';
    }
}
?>
