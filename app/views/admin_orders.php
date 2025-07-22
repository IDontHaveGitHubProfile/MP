<?php
require_once '../database/connect.php';
require_once '../app/modules/admin-sidebar.php';

// Получение заказов
$stmt = $pdo->prepare("
  SELECT o.order_id, CONCAT(u.user_surname, ' ', u.user_name) AS client_name,
         o.order_created_at, o.order_total_price, o.order_status
  FROM orders o
  JOIN users u ON o.user_id = u.user_id
  ORDER BY o.order_created_at DESC
  LIMIT 20
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-main">
  <div class="admin-content">
    <div class="admin-header">
      <h1 class="section-title">Заказы</h1>
    </div>

    <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Статус</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
              <tr>
                <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars($order['client_name']) ?></td>
                <td><?= date('d.m.Y H:i', strtotime($order['order_created_at'])) ?></td>
                <td><?= number_format($order['order_total_price'], 2, ',', ' ') ?>₽</td>
                <td>
                  <span class="admin-status <?= getOrderStatusClass($order['order_status']) ?>">
                    <?= htmlspecialchars($order['order_status']) ?>
                  </span>
                </td>
                <td>
                  <button class="admin-action-btn" title="Просмотр" data-order-id="<?= $order['order_id'] ?>"><i class="fas fa-eye"></i></button>
                  <button class="admin-action-btn" title="Редактировать" data-order-id="<?= $order['order_id'] ?>"><i class="fas fa-edit"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6">Заказы отсутствуют</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
// Вспомогательная функция для CSS-класса по статусу
function getOrderStatusClass($status) {
  $map = [
    'Новый' => 'status-new',
    'В обработке' => 'status-processing',
    'Отправлен' => 'status-shipped',
    'Доставлен' => 'status-delivered',
    'Отменён' => 'status-cancelled'
  ];
  return $map[$status] ?? 'status-unknown';
}
?>
