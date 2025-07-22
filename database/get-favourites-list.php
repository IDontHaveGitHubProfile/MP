<?php

require_once '../database/connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user']['user_id'])) {
    echo json_encode([]);
    exit;
}

$userId = $_SESSION['user']['user_id'];
$stmt = $pdo->prepare("SELECT product_id FROM favourites WHERE user_id = ?");
$stmt->execute([$userId]);

$favourites = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($favourites);
?>