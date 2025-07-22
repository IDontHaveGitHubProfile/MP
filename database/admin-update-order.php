<?php
require_once __DIR__ . '/../database/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
        $order_status = filter_input(INPUT_POST, 'order_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Заменили на фильтр без депрекации
        $order_comment = filter_input(INPUT_POST, 'order_comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Заменили на фильтр без депрекации

        if (!$order_id) {
            throw new Exception('Не указан ID заказа');
        }

        if (!$order_status) {
            throw new Exception('Не указан статус заказа');
        }


        $allowed_statuses = ['Новый', 'В обработке', 'Отправлен', 'Доставлен', 'Отменён'];
        if (!in_array($order_status, $allowed_statuses)) {
            throw new Exception('Недопустимый статус заказа');
        }


        $stmt = $pdo->prepare("UPDATE orders 
                               SET order_status = :status, 
                                   order_comment = :comment,
                                   order_updated_at = NOW()
                               WHERE order_id = :id");
        
        $stmt->execute([ 
            ':status' => $order_status, 
            ':comment' => $order_comment, 
            ':id' => $order_id
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception('Заказ не найден или данные не изменились');
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Заказ успешно обновлен'
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error', 
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Метод не разрешен'
    ]);
}
