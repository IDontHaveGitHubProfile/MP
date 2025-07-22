<?php
require_once '../database/connect.php';
header('Content-Type: application/json');

$userPhone = $_POST['user_phone'] ?? '';
$userPassword = $_POST['user_password'] ?? '';
$rememberMe = isset($_POST['agree_terms']);

try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_phone = ?');
    $stmt->execute([$userPhone]);
    $user = $stmt->fetch();

    if (!$user) throw new Exception('Неверный телефон или пароль');
    if (!password_verify($userPassword, $user['user_password'])) throw new Exception('Неверный телефон или пароль');

    $_SESSION['user'] = [
        'user_id' => $user['user_id'],
        'user_phone' => $user['user_phone'],
        'user_email' => $user['user_email'],
        'user_email_verified' => $user['user_email_verified'],
        'user_email_verified_at' => $user['user_email_verified_at'],
        'email_verification_token' => $user['email_verification_token'],
        'email_verification_token_expires' => $user['email_verification_token_expires'],
        'user_surname' => $user['user_surname'],
        'user_name' => $user['user_name'],
        'password_updated_at' => $user['password_updated_at'],
        'user_created_at' => $user['user_created_at'],
        'name_updated_at' => $user['name_updated_at'],
    ];

    if ($rememberMe) {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expires = time() + 60 * 60 * 24 * 30;
        $stmt = $pdo->prepare('INSERT INTO persistent_logins (user_id, token_hash, expires_at) VALUES (?, ?, ?)');
        $stmt->execute([$user['user_id'], $tokenHash, date('Y-m-d H:i:s', $expires)]);
        setcookie('remember_token', $token, $expires, '/', '', false, true);
    }

    $requireEmailVerification = empty($user['user_email']) || !$user['user_email_verified'];
    $response = [
        'success' => true,
        'user_id' => $user['user_id'],
        'require_email_verification' => $requireEmailVerification,
        'has_email' => !empty($user['user_email'])
    ];
    if (!$requireEmailVerification) $response['redirect'] = 'index.php?page=home';
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}