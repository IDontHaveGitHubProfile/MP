<?php
require_once '../database/connect.php';
require_once '../app/modules/admin-sidebar.php';

// Статистика
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(order_total_price) FROM orders")->fetchColumn();

// Последние заказы
$stmt = $pdo->prepare("
  SELECT o.order_id, CONCAT(u.user_surname, ' ', u.user_name) AS client_name,
         o.order_created_at, o.order_total_price, o.order_status
  FROM orders o
  JOIN users u ON o.user_id = u.user_id
  ORDER BY o.order_created_at DESC
  LIMIT 5
");
$stmt->execute();
$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-main">
  <div class="admin-content">
    <h1 class="section-title">Главная панель</h1>

    <!-- Карточки статистики -->
    <div class="admin-cards">
      <div class="admin-card">
        <div class="admin-card-header">
          <h2>Товары</h2>
          <div class="admin-card-icon">
            <i class="fas fa-box"></i>
          </div>
        </div>
        <div class="admin-card-value"><?= (int)$totalProducts ?></div>
        <div class="admin-card-footer">
          <a href="index.php?page=admin_products">Перейти</a>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h2>Пользователи</h2>
          <div class="admin-card-icon">
            <i class="fas fa-users"></i>
          </div>
        </div>
        <div class="admin-card-value"><?= (int)$totalUsers ?></div>
        <div class="admin-card-footer">
          <a href="index.php?page=admin_users">Перейти</a>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h2>Заказы</h2>
          <div class="admin-card-icon">
            <i class="fas fa-shopping-cart"></i>
          </div>
        </div>
        <div class="admin-card-value"><?= (int)$totalOrders ?></div>
        <div class="admin-card-footer">
          <a href="index.php?page=admin_orders">Перейти</a>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h2>Доход</h2>
          <div class="admin-card-icon">
            <i class="fas fa-ruble-sign"></i>
          </div>
        </div>
        <div class="admin-card-value"><?= number_format($totalRevenue, 2, ',', ' ') ?> ₽</div>
        <div class="admin-card-footer">
          <span>Суммарно</span>
        </div>
      </div>
    </div>

    <!-- Последние заказы -->
    <div class="admin-section">
      <div class="admin-section-header">
        <h2 class="subsection-title">Последние заказы</h2>
        <a href="index.php?page=admin_orders" class="admin-btn admin-btn-outline">Все заказы</a>
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
            <?php if (!empty($recentOrders)): ?>
              <?php foreach ($recentOrders as $order): ?>
                <tr>
                  <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                  <td><?= htmlspecialchars($order['client_name']) ?></td>
                  <td><?= date('d.m.Y H:i', strtotime($order['order_created_at'])) ?></td>
                  <td><?= number_format($order['order_total_price'], 2, ',', ' ') ?> ₽</td>
                  <td>
                    <span class="admin-status"><?= htmlspecialchars($order['order_status']) ?></span>
                  </td>
                  <td>
                    <button class="admin-action-btn" title="Просмотр"><i class="fas fa-eye"></i></button>
                    <button class="admin-action-btn" title="Редактировать"><i class="fas fa-edit"></i></button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="admin-empty">Нет заказов</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
