<?php
require_once '../database/connect.php';
require_once '../app/modules/admin-sidebar.php';

// Поиск пользователей
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? 'all';

// Базовый запрос
$query = "
  SELECT 
    user_id, 
    user_email, 
    user_surname, 
    user_name, 
    user_phone, 
    user_email_verified,
    DATE_FORMAT(user_created_at, '%d.%m.%Y') as reg_date,
    (SELECT COUNT(*) FROM orders WHERE user_id = users.user_id) as orders_count
  FROM users
  WHERE 
    (user_email LIKE :search1
    OR user_name LIKE :search2
    OR user_surname LIKE :search3
    OR user_phone LIKE :search4)
";

// Добавляем фильтр по статусу
if ($status_filter === 'verified') {
  $query .= " AND user_email_verified = 1";
} elseif ($status_filter === 'unverified') {
  $query .= " AND user_email_verified = 0";
}

$query .= " ORDER BY user_id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute([
  'search1' => "%$search%",
  'search2' => "%$search%",
  'search3' => "%$search%",
  'search4' => "%$search%",
]);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Статистика пользователей
$stats_stmt = $pdo->query("
  SELECT 
    COUNT(*) as total_users,
    SUM(user_email_verified) as verified_users,
    COUNT(*) - SUM(user_email_verified) as unverified_users
  FROM users
");
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="admin-main">
  <div class="admin-content">
    <div class="admin-header">
      <h1 class="section-title">Управление клиентами</h1>
    </div>

    <!-- Карточки статистики -->
    <div class="admin-cards">
      <div class="admin-card">
        <div class="admin-card-header">
          <h2>Всего клиентов</h2>
          <div class="admin-card-icon">
            <i class="fas fa-users"></i>
          </div>
        </div>
        <div class="admin-card-value"><?= $stats['total_users'] ?></div>
        <div class="admin-card-footer">
          <span>За всё время</span>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h2>Подтверждённые</h2>
          <div class="admin-card-icon">
            <i class="fas fa-user-check"></i>
          </div>
        </div>
        <div class="admin-card-value"><?= $stats['verified_users'] ?></div>
        <div class="admin-card-footer">
          <span>Email подтверждён</span>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h2>Неподтверждённые</h2>
          <div class="admin-card-icon">
            <i class="fas fa-user-clock"></i>
          </div>
        </div>
        <div class="admin-card-value"><?= $stats['unverified_users'] ?></div>
        <div class="admin-card-footer">
          <span>Требуют проверки</span>
        </div>
      </div>
    </div>




    <!-- Таблица пользователей -->
    <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th width="80">ID</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Email</th>
            <th width="140">Телефон</th>
            <th width="120">Дата регистрации</th>
            <th width="120">Заказов</th>
            <th width="120">Статус</th>
            <th width="140">Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
              <tr>
                <td>#<?= htmlspecialchars($user['user_id']) ?></td>
                <td><?= htmlspecialchars($user['user_name']) ?></td>
                <td><?= htmlspecialchars($user['user_surname']) ?></td>
                <td><?= htmlspecialchars($user['user_email'] ?? '—') ?></td>
                <td><?= htmlspecialchars($user['user_phone']) ?></td>
                <td><?= htmlspecialchars($user['reg_date']) ?></td>
                <td><?= (int)$user['orders_count'] ?></td>
                <td>
                  <?php if ($user['user_email_verified']): ?>
                    <span class="admin-badge badge-green">Подтверждён</span>
                  <?php else: ?>
                    <span class="admin-badge badge-red">Не подтверждён</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="admin_edit_user.php?id=<?= $user['user_id'] ?>" class="admin-action-btn" title="Редактировать">
                    <i class="fas fa-edit"></i>
                  </a>

                  <a href="admin_user_orders.php?id=<?= $user['user_id'] ?>" class="admin-action-btn" title="История заказов">
                    <i class="fas fa-shopping-cart"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="empty-table-msg">
                <div class="empty-table-message">
                  <i class="fas fa-user-slash fa-2x"></i>
                  <p>Клиенты не найдены</p>
                  <?php if (!empty($_GET['search']) || $status_filter !== 'all'): ?>
                    <a href="?page=admin_users" class="admin-btn admin-btn-outline mt-2">
                      Показать всех клиентов
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>