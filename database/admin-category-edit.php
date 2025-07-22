<?php
require_once __DIR__ . '/../database/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_STRING);
        $category_description = filter_input(INPUT_POST, 'category_description', FILTER_SANITIZE_STRING);
        $parent_category = filter_input(INPUT_POST, 'parent_category', FILTER_VALIDATE_INT);

        if (!$category_id || !$category_name || !$category_description) {
            throw new Exception('Не все обязательные данные переданы');
        }


        $stmt = $pdo->prepare("UPDATE categories 
                               SET category_name = :category_name, 
                                   category_description = :category_description, 
                                   parent_id = :parent_category
                               WHERE category_id = :category_id");

        $stmt->execute([
            ':category_name' => $category_name,
            ':category_description' => $category_description,
            ':parent_category' => $parent_category,
            ':category_id' => $category_id
        ]);


        echo json_encode(['status' => 'success', 'message' => 'Категория обновлена']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
