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
        throw new Exception('Требуется авторизация для оформления заказа');
    }


    $stmt = $pdo->prepare("SELECT user_email_verified FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user']['user_id']]);
    $emailVerified = (bool)$stmt->fetchColumn();

    if (!$emailVerified) {
        throw new Exception('Для оформления заказа необходимо подтвердить email. Пожалуйста, проверьте вашу почту.');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Ошибка декодирования JSON: ' . json_last_error_msg());
    }

    if (empty($input['items']) || !is_array($input['items'])) {
        throw new Exception('Не переданы товары для заказа');
    }

    $userId = $_SESSION['user']['user_id'];
    $items = $input['items'];

    if (!$pdo) {
        throw new Exception('Нет подключения к базе данных');
    }

    $stmt = $pdo->prepare("
        SELECT 
            c.product_id, 
            c.cart_quantity, 
            p.product_id, 
            p.product_name, 
            p.product_price, 
            p.product_quantity
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = ?
    ");
    
    if (!$stmt->execute([$userId])) {
        throw new Exception('Ошибка при получении товаров из корзины');
    }

    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $cartMap = array_column($cartItems, null, 'product_id');

    $validItems = [];
    foreach ($items as $item) {
        if (empty($item['productId']) || empty($item['quantity'])) {
            continue;
        }

        $productId = (int)$item['productId'];
        $quantity = (int)$item['quantity'];

        if (!isset($cartMap[$productId])) {
            continue;
        }

        $available = (int)$cartMap[$productId]['product_quantity'];
        if ($quantity > 0 && $quantity <= $available) {
            $validItems[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => (float)$cartMap[$productId]['product_price'],
                'name' => $cartMap[$productId]['product_name']
            ];
        }
    }

    if (empty($validItems)) {
        throw new Exception('Нет доступных товаров для заказа');
    }

    if (count($validItems) !== count($items)) {
        throw new Exception('Некоторые товары недоступны или закончились');
    }

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("
            INSERT INTO orders 
                (user_id, order_status, order_total_price, order_created_at) 
            VALUES 
                (:user_id, 'В обработке', :total, NOW())
        ");
        
        $total = array_reduce($validItems, fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);
        
        if (!$stmt->execute([':user_id' => $userId, ':total' => $total])) {
            throw new Exception('Ошибка создания заказа');
        }
        
        $orderId = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare("
            INSERT INTO order_items 
                (order_id, product_id, order_item_quantity, order_item_price) 
            VALUES 
                (:order_id, :product_id, :quantity, :price)
        ");

        foreach ($validItems as $item) {
            if (!$itemStmt->execute([
                ':order_id' => $orderId,
                ':product_id' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price']
            ])) {
                throw new Exception('Ошибка добавления товара в заказ');
            }

            $updateStmt = $pdo->prepare("
                UPDATE products 
                SET product_quantity = product_quantity - :quantity 
                WHERE product_id = :product_id
            ");
            
            if (!$updateStmt->execute([
                ':quantity' => $item['quantity'],
                ':product_id' => $item['product_id']
            ])) {
                throw new Exception('Ошибка обновления остатков');
            }

            $deleteStmt = $pdo->prepare("
                DELETE FROM cart 
                WHERE user_id = :user_id AND product_id = :product_id
            ");
            
            if (!$deleteStmt->execute([
                ':user_id' => $userId,
                ':product_id' => $item['product_id']
            ])) {
                throw new Exception('Ошибка удаления из корзины');
            }
        }

        $pdo->commit();

        $_SESSION['order_success'] = [
            'order_id' => $orderId,
            'total' => $total,
            'items' => $validItems,
            'timestamp' => time()
        ];

        session_write_close();

        echo json_encode([
            'status' => 'success',
            'message' => 'Заказ успешно оформлен',
            'order_id' => $orderId,
            'redirect' => '/waza/public/index.php?page=profile&order_success=1&t=' . time()
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log('PDO Error: ' . $e->getMessage());
    $_SESSION['order_error'] = 'Ошибка базы данных';
    echo json_encode([
        'status' => 'error',
        'message' => 'Ошибка базы данных'
    ]);
} catch (Exception $e) {
    error_log('Order Error: ' . $e->getMessage());
    $_SESSION['order_error'] = $e->getMessage();
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}