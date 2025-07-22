<?php
require_once '../database/connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

$userId = $_SESSION['user']['user_id'];
$newName = trim($_POST['user_name'] ?? '');
$newSurname = trim($_POST['user_surname'] ?? '');

if (strlen($newName) < 2 || strlen($newSurname) < 2) {
    echo json_encode(['status' => 'invalid']);
    exit;
}

$stmt = $pdo->prepare("SELECT user_name, user_surname, name_updated_at, user_created_at FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['status' => 'not_found']);
    exit;
}

if ($newName === $user['user_name'] && $newSurname === $user['user_surname']) {
    echo json_encode(['status' => 'no_changes']);
    exit;
}

$createdAt = new DateTime($user['user_created_at']);
$updatedAt = new DateTime($user['name_updated_at']);
$now = new DateTime();

if ($createdAt->format('Y-m-d H:i:s') !== $updatedAt->format('Y-m-d H:i:s')) {
    $nextChangeDate = (clone $updatedAt)->modify('+1 month');
    if ($now < $nextChangeDate) {
        echo json_encode([
            'status' => 'wait',
            'next_change' => $nextChangeDate->format('Y-m-d')
        ]);
        exit;
    }
}

$stmt = $pdo->prepare("UPDATE users SET user_name = ?, user_surname = ?, name_updated_at = NOW() WHERE user_id = ?");
if ($stmt->execute([$newName, $newSurname, $userId])) {
    $nextChangeDate = (new DateTime())->modify('+1 month');
    echo json_encode([
        'status' => 'success',
        'next_change' => $nextChangeDate->format('Y-m-d')
    ]);
} else {
    echo json_encode(['status' => 'error']);
}
