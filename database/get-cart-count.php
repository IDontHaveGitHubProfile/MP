<?php
require_once '../database/connect.php'; // Затем используем BASE_PATH
header('Content-Type: application/json; charset=utf-8');

$count = 0;
if (isset($_SESSION['user']['user_id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user']['user_id']]);
    $count = (int)$stmt->fetchColumn();
}

echo json_encode([
    'success' => true,
    'cartCount' => $count
]);
exit;
?>