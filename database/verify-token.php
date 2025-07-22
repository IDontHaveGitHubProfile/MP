<?php
require_once __DIR__ . '/connect.php';
header('Content-Type: application/json');

$token = $_GET['token'] ?? null;

if (!$token) {
    echo json_encode(['status' => 'error', 'message' => 'Токен не указан']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT user_id, is_subscribed_to_news 
        FROM users 
        WHERE email_verification_token = :token 
        AND email_verification_token_expires > NOW()
    ");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if (!$user) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Неверный или просроченный токен']);
        exit;
    }

    $updateFields = "
        user_email_verified_at = NOW(), 
        user_email_verified = 1,
        email_verification_token = NULL,
        email_verification_token_expires = NULL
    ";

    if ($user['is_subscribed_to_news'] == 1) {
        $updateFields .= ", news_subscription_updated_at = NOW(), news_subscription_token = NULL";
    }

    $updateStmt = $pdo->prepare("
        UPDATE users 
        SET {$updateFields}
        WHERE user_id = :user_id
    ");
    $updateStmt->execute(['user_id' => $user['user_id']]);

    $_SESSION['show_email_success'] = true;
    $_SESSION['email_verified_user_id'] = $user['user_id'];

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Email успешно подтвержден',
        'redirect' => '/folder/public/index.php?page=profile',
        'is_subscribed' => (bool)$user['is_subscribed_to_news']
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Verify Token Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Ошибка базы данных']);
}