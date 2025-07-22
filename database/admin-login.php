<?php
require_once __DIR__ . '/../database/connect.php';
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не разрешён']);
    exit;
}


$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');


if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Введите логин и пароль.']);
    exit;
}


$adminCredentials = require __DIR__ . '/../database/admin-credentials.php';

if (!isset($adminCredentials['login']) || !isset($adminCredentials['password'])) {
    echo json_encode(['success' => false, 'message' => 'Ошибка в файле admin-credentials']);
    exit;
}


if ($username !== $adminCredentials['login'] || !password_verify($password, $adminCredentials['password'])) {
    echo json_encode(['success' => false, 'message' => 'Неверный логин или пароль']);
    exit;
}

$_SESSION['user'] = [
    'admin' => true,
    'login' => $username
];


echo json_encode(['success' => true]);
exit;
