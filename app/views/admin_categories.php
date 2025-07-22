<?php
require_once '../database/connect.php';
require_once '../app/modules/admin-sidebar.php';

// Поиск категории
$search = $_GET['search'] ?? '';
$query = "
  SELECT category_id, category_name, parent_id, category_description, category_image,
         (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id) AS product_count,
         (SELECT COUNT(*) FROM categories c2 WHERE c2.parent_id = c.category_id) AS subcategory_count
  FROM categories c
  WHERE category_name LIKE :search
  ORDER BY category_id DESC
";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем все категории для выпадающего списка (родительские категории)
$allCategoriesStmt = $pdo->query("SELECT category_id, category_name FROM categories");
$allCategories = $allCategoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-main">
  <div class="admin-content">
    <div class="admin-header">
      <h1 class="section-title">Категории</h1>
<button id="addCategoryBtn" class="admin-btn admin-btn-primary">
    <i class="fas fa-plus"></i> Добавить категорию
</button>
    </div>

    <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th width="60">ID</th>
            <th>Название</th>
            <th>Родитель</th>
            <th>Описание</th>
            <th>Товары</th>
            <th>Подкатегории</th>
            <th width="120">Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $category): ?>
            <tr data-id="<?= $category['category_id'] ?>">
              <td><?= htmlspecialchars($category['category_id']) ?></td>
              <td>
                <strong><?= htmlspecialchars($category['category_name']) ?></strong>
              </td>
              <td>
                <?php
                if ($category['parent_id']) {
                  $parentStmt = $pdo->prepare("SELECT category_name FROM categories WHERE category_id = ?");
                  $parentStmt->execute([$category['parent_id']]);
                  $parent = $parentStmt->fetch(PDO::FETCH_ASSOC);
                  echo htmlspecialchars($parent['category_name'] ?? '—');
                } else {
                  echo '<span class="text-muted">—</span>';
                }
                ?>
              </td>
              <td class="admin-description">
                <?= !empty($category['category_description']) 
                    ? htmlspecialchars(mb_substr($category['category_description'], 0, 50) . (mb_strlen($category['category_description']) > 50 ? '...' : '')) 
                    : '<span class="text-muted">—</span>' ?>
              </td>
              <td>
                <?= $category['product_count'] ?> товаров
              </td>
              <td>
                <?= $category['subcategory_count'] ?> подкатегорий
              </td>
              <td>
                <button class="admin-action-btn edit-category-btn" data-category-id="<?= $category['category_id'] ?>" title="Редактировать">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="admin-action-btn delete-category-btn" data-category-id="<?= $category['category_id'] ?>" title="Удалить">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($categories)): ?>
            <tr>
              <td colspan="7" class="text-center py-4">
                <div class="empty-table-message">
                  <i class="fas fa-folder-open fa-2x"></i>
                  <p>Категории не найдены</p>
                  <?php if (!empty($_GET['search'])): ?>
                    <a href="?page=admin_categories" class="admin-btn admin-btn-outline mt-2">
                      Показать все категории
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
