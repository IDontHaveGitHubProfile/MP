<?php
require_once __DIR__ . '/../database/connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Используем более безопасный метод
        $category_description = filter_input(INPUT_POST, 'category_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $parent_id = filter_input(INPUT_POST, 'parent_category', FILTER_VALIDATE_INT) ?: null;

        if (!$category_name || !$category_description) {
            throw new Exception('Не все обязательные данные переданы');
        }


        $stmt = $pdo->prepare("INSERT INTO categories (category_name, category_description, parent_id) 
                               VALUES (:category_name, :category_description, :parent_id)");
        $stmt->execute([
            ':category_name' => $category_name,
            ':category_description' => $category_description,
            ':parent_id' => $parent_id
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Категория добавлена']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
