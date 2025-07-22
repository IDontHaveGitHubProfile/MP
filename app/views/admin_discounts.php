<?php
require_once '../database/connect.php';
require_once '../app/modules/admin-sidebar.php';

// Поиск по названию скидки или типу
$search = $_GET['search'] ?? '';
$query = "
  SELECT d.discount_id, d.discount_name, d.discount_value, d.discount_type, d.status, d.start_date, d.end_date, p.product_name
  FROM discounts d
  LEFT JOIN discount_product dp ON d.discount_id = dp.discount_id
  LEFT JOIN products p ON dp.product_id = p.product_id
  WHERE d.discount_name LIKE :search OR d.discount_type LIKE :search2
  ORDER BY d.discount_created_at DESC
  LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($query);

// Пагинация
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Привязка параметров
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':search2', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение общего числа скидок для пагинации
$countQuery = "
  SELECT COUNT(*) as total
  FROM discounts d
  LEFT JOIN discount_product dp ON d.discount_id = dp.discount_id
  LEFT JOIN products p ON dp.product_id = p.product_id
  WHERE d.discount_name LIKE :search OR d.discount_type LIKE :search2
";
$countStmt = $pdo->prepare($countQuery);
$countStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$countStmt->bindValue(':search2', "%$search%", PDO::PARAM_STR);
$countStmt->execute();
$totalDiscounts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalDiscounts / $limit);
?>

<div class="admin-main">
  <div class="admin-content">
    <div class="admin-header">
      <h1 class="section-title">Управление скидками</h1>
      <a href="admin_add_discount.php" class="admin-btn admin-btn-primary">
        <i class="fas fa-plus"></i> Добавить скидку
      </a>
    </div>

    <div class="admin-filters">
      <form method="get" class="admin-search-form">
        <input type="text" name="search" placeholder="Поиск по названию скидки или типу..." value="<?= htmlspecialchars($search) ?>" class="admin-search-input">
        <button type="submit" class="admin-btn admin-btn-outline">Найти</button>
      </form>
    </div>

    <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID скидки</th>
            <th>Название скидки</th>
            <th>Тип скидки</th>
            <th>Значение скидки</th>
            <th>Товары</th>
            <th>Статус</th>
            <th>Дата начала</th>
            <th>Дата окончания</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($discounts)): ?>
            <?php foreach ($discounts as $discount): ?>
              <tr>
                <td>#<?= htmlspecialchars($discount['discount_id']) ?></td>
                <td><?= htmlspecialchars($discount['discount_name']) ?></td>
                <td><?= htmlspecialchars($discount['discount_type']) ?></td>
                <td>
                  <?= htmlspecialchars($discount['discount_value']) ?>
                  <?= $discount['discount_type'] == 'percentage' ? '%' : '₽' ?>
                </td>
                <td><?= htmlspecialchars($discount['product_name'] ?? 'Нет товара') ?></td>
                <td><?= htmlspecialchars($discount['status']) ?></td>
                <td><?= htmlspecialchars($discount['start_date'] ?? 'Не указано') ?></td>
                <td><?= htmlspecialchars($discount['end_date'] ?? 'Не указано') ?></td>
                <td>
                  <button class="admin-action-btn" title="Редактировать">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="admin-action-btn" title="Удалить">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="empty-table-msg">Нет скидок по данному запросу</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
