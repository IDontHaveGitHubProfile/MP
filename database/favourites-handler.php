<?php

require_once '../database/connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user']['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$productId = isset($data['product_id']) ? (int)$data['product_id'] : 0;
$action = $data['action'] ?? '';

if ($productId <= 0 || $action !== 'toggle') {
    echo json_encode(['success' => false, 'message' => 'Неверные данные']);
    exit;
}

$userId = (int)$_SESSION['user']['user_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM favourites WHERE user_id = ? AND product_id = ?");
$stmt->execute([$userId, $productId]);
$exists = (bool)$stmt->fetchColumn();

if ($exists) {
    $stmt = $pdo->prepare("DELETE FROM favourites WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    echo json_encode(['success' => true, 'action' => 'removed']);
    exit;
} else {
    $stmt = $pdo->prepare("INSERT INTO favourites (user_id, product_id, favourite_added_to) VALUES (?, ?, NOW())");
    $stmt->execute([$userId, $productId]);
    echo json_encode(['success' => true, 'action' => 'added']);
    exit;
}
