<?php
require_once __DIR__ . '/connect.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
    exit;
}

$userId = $_SESSION['user']['user_id'];
$currentTime = date('Y-m-d H:i:s');


$stmt = $pdo->prepare("
    SELECT 
        user_email,
        email_verification_token_expires AS expires
    FROM users 
    WHERE 
        user_id = ? AND
        email_verification_token IS NOT NULL AND
        email_verification_token_expires > ?
");
$stmt->execute([$userId, $currentTime]);
$tokenData = $stmt->fetch();

if ($tokenData) {
    echo json_encode([
        'status' => 'active',
        'email' => $tokenData['user_email'],
        'expires' => $tokenData['expires']
    ]);
} else {

    $pdo->prepare("
        UPDATE users 
        SET 
            email_verification_token = NULL,
            email_verification_token_expires = NULL
        WHERE 
            user_id = ? AND
            (email_verification_token_expires IS NOT NULL AND 
             email_verification_token_expires <= ?)
    ")->execute([$userId, $currentTime]);
    
    echo json_encode(['status' => 'inactive']);
}
?>