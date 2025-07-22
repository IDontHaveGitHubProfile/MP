<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../database/connect.php';
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    if (!isset($_SESSION['user']['user_id'])) {
        throw new Exception('Требуется авторизация');
    }

    if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
        throw new Exception('Неверный идентификатор заказа');
    }

    $orderId = (int)$_GET['order_id'];
    $userId = $_SESSION['user']['user_id'];


    $stmt = $pdo->prepare("SELECT order_id FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->execute([$orderId, $userId]);
    
    if (!$stmt->fetch()) {
        throw new Exception('Заказ не найден');
    }


    $updateStmt = $pdo->prepare("UPDATE orders SET viewed_by_user = 1 WHERE order_id = ?");
    $updateStmt->execute([$orderId]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Заказ отмечен как просмотренный',
        'order_id' => $orderId
    ]);

} catch (PDOException $e) {
    error_log('PDO Error: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Ошибка базы данных'
    ]);
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}