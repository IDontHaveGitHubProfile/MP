<?php

require_once '../database/connect.php';

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

if ($categoryId > 0) {
    try {

        $query = 'SELECT category_id, category_name FROM categories WHERE parent_id = :categoryId';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($subcategories)) {
            echo json_encode($subcategories); // Отправляем JSON
        } else {
            echo json_encode([]); // Нет подкатегорий
        }

    } catch (PDOException $e) {

        echo json_encode(['error' => 'Ошибка базы данных']);
    }
} else {
    echo json_encode([]);
}
?>
