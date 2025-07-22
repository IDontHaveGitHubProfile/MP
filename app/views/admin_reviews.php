<?php
require_once '../database/connect.php';
require_once '../app/modules/admin-sidebar.php';

// Поиск по комментарию или продукту
$search = $_GET['search'] ?? '';
$query = "
  SELECT r.review_id, r.rating, r.comment, r.review_created_at, r.updated_comment, p.product_name, u.user_name
  FROM reviews r
  JOIN products p ON r.product_id = p.product_id
  JOIN users u ON r.user_id = u.user_id
  WHERE r.comment LIKE :search OR p.product_name LIKE :search2
  ORDER BY r.review_created_at DESC
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
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение общего числа отзывов для пагинации
$countQuery = "
  SELECT COUNT(*) as total
  FROM reviews r
  JOIN products p ON r.product_id = p.product_id
  JOIN users u ON r.user_id = u.user_id
  WHERE r.comment LIKE :search OR p.product_name LIKE :search2
";
$countStmt = $pdo->prepare($countQuery);
$countStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$countStmt->bindValue(':search2', "%$search%", PDO::PARAM_STR);
$countStmt->execute();
$totalReviews = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalReviews / $limit);
?>

<div class="admin-main">
  <div class="admin-content">
    <div class="admin-header">
      <h1 class="section-title">Управление отзывами</h1>
      <!-- Убрали кнопку добавления отзыва -->
    </div>

    <div class="admin-filters">
      <form method="get" class="admin-search-form">
        <input type="text" name="search" placeholder="Поиск по товарам или комментариям..." value="<?= htmlspecialchars($search) ?>" class="admin-search-input">
        <button type="submit" class="admin-btn admin-btn-outline">Найти</button>
      </form>
    </div>

    <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID отзыва</th>
            <th>Продукт</th>
            <th>Пользователь</th>
            <th>Рейтинг</th>
            <th>Комментарий</th>
            <th>Дата создания</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
              <tr>
                <td>#<?= htmlspecialchars($review['review_id']) ?></td>
                <td><?= htmlspecialchars($review['product_name']) ?></td>
                <td><?= htmlspecialchars($review['user_name']) ?></td>
                <td><?= (int)$review['rating'] ?> / 5</td>
                <td>
                  <?= htmlspecialchars(strlen($review['comment']) > 50 ? substr($review['comment'], 0, 50) . '...' : $review['comment']) ?>
                </td>
                <td><?= htmlspecialchars($review['review_created_at']) ?></td>
                <td>
                  <button class="admin-action-btn" title="Редактировать"><i class="fas fa-edit"></i></button>
                  <button class="admin-action-btn" title="Удалить"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="empty-table-msg">Нет отзывов по данному запросу</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Убрали пагинацию -->
  </div>
</div>
