<?php
require_once __DIR__ . '/../database/connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);

        if (!$category_id) {
            throw new Exception('Не указан ID категории');
        }


        $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->execute([$category_id]);

        echo json_encode(['status' => 'success', 'message' => 'Категория удалена']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
