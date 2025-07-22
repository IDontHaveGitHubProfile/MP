<?php
require_once '../database/connect.php'; // Затем используем BASE_PATH
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false]);
    exit;
}

$user_id = $_SESSION['user']['user_id'];

$sql = "SELECT user_name, user_surname, name_updated_at, user_created_at FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

if ($user) {
    $months = [
        'January' => 'января', 'February' => 'февраля', 'March' => 'марта',
        'April' => 'апреля', 'May' => 'мая', 'June' => 'июня',
        'July' => 'июля', 'August' => 'августа', 'September' => 'сентября',
        'October' => 'октября', 'November' => 'ноября', 'December' => 'декабря'
    ];
    
    $lastUpdate = $user['name_updated_at'];
    $nextChangeDate = $lastUpdate ? date("d F Y", strtotime('+1 month', strtotime($lastUpdate))) : null;
    $nextChangeDateFormatted = $nextChangeDate ? strtr($nextChangeDate, $months) : null;

    echo json_encode([
        'success' => true,
        'user_name' => $user['user_name'],
        'user_surname' => $user['user_surname'],
        'name_updated_at' => $lastUpdate,
        'user_created_at' => $user['user_created_at'],
        'next_change' => $nextChangeDateFormatted
    ]);
} else {
    echo json_encode(['success' => false]);
}