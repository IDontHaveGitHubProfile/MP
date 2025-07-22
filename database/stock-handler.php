<?php
require_once '../database/connect.php';

header('Content-Type: application/json');


if (empty($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    echo json_encode(['error' => 'Invalid product ID']);
    exit;
}

$product_id = (int)$_GET['product_id'];

try {
    $query = $pdo->prepare("SELECT product_quantity FROM products WHERE product_id = ?");
    $query->execute([$product_id]);
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo json_encode([
            'product_quantity' => (int)$product['product_quantity']
        ]);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error']);
}
?>
