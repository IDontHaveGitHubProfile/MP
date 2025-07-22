<?php
require_once '../database/connect.php';
require_once '../app/modules/admin-sidebar.php';

$search = $_GET['search'] ?? '';
?>

<div class="admin-main">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="section-title">Управление товарами</h1>
            <button id="addProductBtn" class="admin-btn admin-btn-primary">
                <i class="fas fa-plus"></i> Добавить товар
            </button>
        </div>



        <div class="admin-table-container">
            <?php
            $query = "SELECT p.product_id, p.product_name, p.product_price, p.product_quantity, c.category_name
                      FROM products p
                      JOIN categories c ON p.category_id = c.category_id
                      WHERE p.product_name LIKE :search
                      ORDER BY p.product_id DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['search' => "%$search%"]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($product['product_id']) ?></td>
                                <td><?= htmlspecialchars($product['product_name']) ?></td>
                                <td><?= htmlspecialchars($product['category_name']) ?></td>
                                <td><?= number_format($product['product_price'], 2, ',', ' ') ?> ₽</td>
                                <td>
                                    <?php
                                        $qty = (int)$product['product_quantity'];
                                        $qtyClass = '';
                                        $qtyText = $qty;

                                        if ($qty === 0) {
                                            $qtyClass = 'qty-out';
                                            $qtyText = 'Нет в наличии';
                                        } elseif ($qty <= 5) {
                                            $qtyClass = 'qty-low';
                                            $qtyText = $qty . ' (мало)';
                                        }
                                    ?>
                                    <span class="<?= $qtyClass ?>"><?= $qtyText ?></span>
                                </td>
                                <td>
                                    <button class="admin-action-btn" title="Редактировать" 
                                            data-action="edit" data-product-id="<?= $product['product_id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="admin-action-btn" title="Удалить" 
                                            data-action="delete" data-product-id="<?= $product['product_id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-table-msg">Нет товаров по данному запросу</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>