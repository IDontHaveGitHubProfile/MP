<?php
session_start();
require_once '../database/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

$user_id = $_SESSION['user']['user_id'];

try {
    
    $stmt = $pdo->prepare("
        SELECT 
            c.cart_id,
            c.product_id,
            c.cart_quantity,
            p.product_name,
            p.product_price,
            p.product_sku,
            p.product_quantity as stock,
            GROUP_CONCAT(d.discount_id) AS discount_ids,
            GROUP_CONCAT(d.discount_value) AS discount_values,
            GROUP_CONCAT(d.discount_type) AS discount_types,
            EXISTS(SELECT 1 FROM favourites f WHERE f.user_id = ? AND f.product_id = p.product_id) as in_favourites
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        LEFT JOIN discount_product dp ON p.product_id = dp.product_id
        LEFT JOIN discounts d ON dp.discount_id = d.discount_id AND d.status = 'active'
        WHERE c.user_id = ?
        GROUP BY c.cart_id, c.product_id, c.cart_quantity, p.product_name, p.product_price, p.product_sku, p.product_quantity
    ");
    $stmt->execute([$user_id, $user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $totalCount = 0;
    $totalPrice = 0;
    $selectedCount = 0;
    $selectedPrice = 0;

    foreach ($cartItems as &$item) {

        $item['final_price'] = $item['product_price'];
        
        if (!empty($item['discount_ids'])) {
            $discountIds = explode(',', $item['discount_ids']);
            $discountValues = explode(',', $item['discount_values']);
            $discountTypes = explode(',', $item['discount_types']);
            
            foreach ($discountIds as $i => $discountId) {
                if ($discountTypes[$i] == 'percentage') {
                    $discountAmount = $item['product_price'] * ($discountValues[$i] / 100);
                } else {
                    $discountAmount = $discountValues[$i];
                }
                $item['final_price'] = max(0, $item['final_price'] - $discountAmount);
            }
        }
        
        $totalCount += $item['cart_quantity'];
        $totalPrice += $item['final_price'] * $item['cart_quantity'];
    }

    echo json_encode([
        'success' => true,
        'cartItems' => $cartItems,
        'totals' => [
            'total_count' => $totalCount,
            'total_price' => $totalPrice,
            'selected_count' => $selectedCount,
            'selected_price' => $selectedPrice
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>