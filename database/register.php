<?php
require_once '../database/connect.php';

header('Content-Type: application/json');

error_log('Register request: ' . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
    exit;
}

$required_fields = ['user_surname', 'user_name', 'user_phone', 'user_password', 'user_password_confirm'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        echo json_encode(['success' => false, 'errors' => [$field => 'Вы пропустили это поле']]);
        exit;
    }
}

$user_surname = trim($_POST['user_surname']);
$user_name = trim($_POST['user_name']);
$user_password = trim($_POST['user_password']);
$user_confirm_password = trim($_POST['user_password_confirm']);
$user_terms = isset($_POST['user_terms']) && $_POST['user_terms'] === 'on';

$errors = [];

if (mb_strlen($user_name) < 2 || mb_strlen($user_name) > 15) {
    $errors['user_name'] = 'Имя должно быть от 2 до 15 символов';
}
if (mb_strlen($user_surname) < 2 || mb_strlen($user_surname) > 36) {
    $errors['user_surname'] = 'Фамилия должна быть от 2 до 36 символов';
}

if (strlen($user_password) < 8) {
    $errors['user_password'] = 'Пароль должен содержать минимум 8 символов';
} elseif ($user_password !== $user_confirm_password) {
    $errors['user_password_confirm'] = 'Пароли не совпадают';
}

$user_phone = trim($_POST['user_phone']);
$clean_phone = preg_replace('/\D+/', '', $user_phone); 

if (strlen($clean_phone) !== 11 || !preg_match('/^7\d{10}$/', $clean_phone)) {
    $errors['user_phone'] = 'Некорректный номер телефона';
} else {

    $formatted_phone = '+7 ' . substr($clean_phone, 1, 3) . ' ' . substr($clean_phone, 4, 3) . ' ' . substr($clean_phone, 7, 2) . ' ' . substr($clean_phone, 9, 2);
}

if (!$user_terms) {
    $errors['user_terms'] = 'Вы должны согласиться с условиями использования';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

try {

    $stmt = $pdo->prepare("
        SELECT user_id FROM users 
        WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(user_phone, ' ', ''), '+', ''), '-', ''), '(', ''), ')', '') = ?
    ");
    $stmt->execute([$clean_phone]);

    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'errors' => ['user_phone' => 'Этот номер уже зарегистрирован']]);
        exit;
    }

    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (user_surname, user_name, user_phone, user_password, user_created_at, name_updated_at) 
    VALUES (?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $user_surname,
        $user_name,
        $formatted_phone,
        $hashed_password
    ]);

    if ($result) {

        $userId = $pdo->lastInsertId();
        

        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        echo json_encode([
            'success' => true,
            'redirect' => 'index.php?page=home',
            'show_reg_success' => true,
            'message' => 'Регистрация прошла успешно. Теперь вы можете войти.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка при регистрации']);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка сервера. Попробуйте позже.',
        'error_details' => $e->getMessage()
    ]);
}
?>