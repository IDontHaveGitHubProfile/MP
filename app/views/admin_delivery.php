<?php
require_once '../database/connect.php';
require_once '../app/modules/admin-sidebar.php';

// Поиск по адресу, городу или статусу доставки
$search = $_GET['search'] ?? '';
$query = "
  SELECT d.delivery_id, d.delivery_method, d.delivery_address, d.delivery_city, d.delivery_postcode, 
         d.delivery_phone, d.delivery_status, d.delivery_created_at, o.order_id
  FROM delivery d
  JOIN orders o ON d.order_id = o.order_id
  WHERE d.delivery_address LIKE :search OR d.delivery_city LIKE :search2 OR d.delivery_status LIKE :search3
  ORDER BY d.delivery_created_at DESC
  LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($query);

// Пагинация
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Bind parameters
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':search2', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':search3', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение общего числа доставок для пагинации
$countQuery = "
  SELECT COUNT(*) as total
  FROM delivery d
  JOIN orders o ON d.order_id = o.order_id
  WHERE d.delivery_address LIKE :search OR d.delivery_city LIKE :search2 OR d.delivery_status LIKE :search3
";
$countStmt = $pdo->prepare($countQuery);
$countStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$countStmt->bindValue(':search2', "%$search%", PDO::PARAM_STR);
$countStmt->bindValue(':search3', "%$search%", PDO::PARAM_STR);
$countStmt->execute();
$totalDeliveries = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalDeliveries / $limit);
?>

<div class="admin-main">
  <div class="admin-content">
    <div class="admin-header">
      <h1 class="section-title">Управление доставками</h1>
      <!-- Убрана кнопка добавления доставки -->
    </div>

    <div class="admin-filters">
      <form method="get" class="admin-search-form">
        <input type="text" name="search" placeholder="Поиск по адресу, городу или статусу..." value="<?= htmlspecialchars($search) ?>" class="admin-search-input">
        <button type="submit" class="admin-btn admin-btn-outline">Найти</button>
      </form>
    </div>

    <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID доставки</th>
            <th>Метод доставки</th>
            <th>Адрес доставки</th>
            <th>Город</th>
            <th>Почтовый индекс</th>
            <th>Телефон</th>
            <th>Статус</th>
            <th>Дата создания</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($deliveries)): ?>
            <?php foreach ($deliveries as $delivery): ?>
              <tr>
                <td>#<?= htmlspecialchars($delivery['delivery_id']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_method']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_address']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_city']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_postcode']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_phone']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_status']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_created_at']) ?></td>
                <td>
                  <button class="admin-action-btn" title="Редактировать"><i class="fas fa-edit"></i></button>
                  <button class="admin-action-btn" title="Удалить"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="empty-table-msg">Нет доставок по данному запросу</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Убрали пагинацию -->
  </div>
</div>
