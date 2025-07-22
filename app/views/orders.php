<?php

require_once '../database/connect.php';

// Получаем все заказы текущего пользователя
$userId = $_SESSION['user']['user_id'];
$query = "
  SELECT o.order_id, o.order_created_at, o.order_status, o.order_total_price
  FROM orders o
  WHERE o.user_id = :user_id
  ORDER BY o.order_created_at DESC
";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="order-container">
    <h2 class="page-title">Мои заказы</h2>

    <?php if (empty($orders)): ?>
        <div class="no-orders-message">
            <i class="fas fa-box-open"></i>
            <p>У вас нет заказов.</p>
        </div>
    <?php else: ?>
        <div class="order-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-card-header">
                        <p class="order-date">Дата заказа: <?= date('d-m-Y', strtotime($order['order_created_at'])) ?></p>
                        <p class="order-status <?= strtolower(str_replace(' ', '-', $order['order_status'])) ?>"><?= $order['order_status'] ?></p>
                    </div>
                    <div class="order-card-body">
                        <p><strong>Сумма: </strong><span class="order-price"><?= $order['order_total_price'] ?> ₽</span></p>
                        <a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="order-details-link">Подробнее</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
