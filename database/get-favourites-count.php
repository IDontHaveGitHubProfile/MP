<?php

require_once '../database/connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user']['user_id'])) {
    echo json_encode(['favCount' => 0]);
    exit;
}

$userId = $_SESSION['user']['user_id'];
$stmt = $pdo->prepare("SELECT COUNT(*) FROM favourites WHERE user_id = ?");
$stmt->execute([$userId]);
$count = $stmt->fetchColumn();

echo json_encode(['favCount' => (int)$count]);
?>