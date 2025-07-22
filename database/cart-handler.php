<?php

require '../database/connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user']['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user']['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $product_id = $_POST['product_id'] ?? null;

    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Не указан товар']);
        exit;
    }


    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {

        $update = $pdo->prepare("UPDATE cart SET cart_quantity = cart_quantity + 1 WHERE user_id = ? AND product_id = ?");
        $update->execute([$user_id, $product_id]);
    } else {

        $insert = $pdo->prepare("INSERT INTO cart (user_id, product_id, cart_quantity) VALUES (?, ?, 1)");
        $insert->execute([$user_id, $product_id]);
    }

    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'remove') {
    $product_id = $_POST['product_id'] ?? null;

    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Не указан товар']);
        exit;
    }

    $delete = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $delete->execute([$user_id, $product_id]);

    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'check') {
    $stmt = $pdo->prepare("SELECT product_id FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['success' => true, 'in_cart' => $items]);
    exit;
}


echo json_encode(['success' => false, 'message' => 'Некорректное действие']);
exit;
