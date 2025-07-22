<?php
require_once __DIR__ . '/../../database/connect.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;
$is_edit = isset($_GET['edit']) && $_GET['edit'] == 'true';

if (!$order_id) die('<div class="error-message">ID заказа не указан</div>');


$stmt = $pdo->prepare("SELECT o.order_id, o.order_total_price, o.order_status, o.order_created_at, o.order_comment,
                              u.user_surname, u.user_name, u.user_phone, u.user_email
                       FROM orders o
                       JOIN users u ON o.user_id = u.user_id
                       WHERE o.order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) die('<div class="error-message">Заказ не найден</div>');


$stmt_items = $pdo->prepare("SELECT oi.order_item_id, p.product_name, oi.order_item_quantity, oi.order_item_price
                             FROM order_items oi
                             JOIN products p ON oi.product_id = p.product_id
                             WHERE oi.order_id = ?");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);


$total_price = 0;
foreach ($items as $item) {
    $total_price += $item['order_item_quantity'] * $item['order_item_price'];
}

$statuses = ['Новый', 'В обработке', 'Отправлен', 'Доставлен', 'Отменён'];
?>

<?php if ($is_edit): ?>

<div id="orderModalEdit" class="admin-modal-overlay">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>Изменить заказ #<?= $order['order_id'] ?></h2>
            <button class="admin-modal-close">×</button>
        </div>
        <div class="admin-modal-body">
            <form id="editOrderForm">
                <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                
                <div class="order-info-grid">
                    <div class="order-info-item">
                        <span class="info-label">Клиент:</span>
                        <span class="info-value"><?= htmlspecialchars($order['user_surname']) . ' ' . htmlspecialchars($order['user_name']) ?></span>
                    </div>
                    <div class="order-info-item">
                        <span class="info-label">Телефон:</span>
                        <span class="info-value"><?= htmlspecialchars($order['user_phone']) ?></span>
                    </div>
                    <div class="order-info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?= htmlspecialchars($order['user_email']) ?></span>
                    </div>
                    <div class="order-info-item">
                        <span class="info-label">Дата заказа:</span>
                        <span class="info-value"><?= date('d.m.Y H:i', strtotime($order['order_created_at'])) ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="order_status" class="form-label">Статус заказа:</label>
                    <select name="order_status" id="order_status" class="form-select" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status ?>" <?= ($status == $order['order_status']) ? 'selected' : '' ?>>
                                <?= $status ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="order_comment" class="form-label">Комментарий:</label>
                    <textarea id="order_comment" name="order_comment" class="form-textarea"><?= htmlspecialchars($order['order_comment'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <button type="button" class="btn btn-secondary close-popup">Закрыть</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php else: ?>

<div id="orderModalView" class="admin-modal-overlay">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>Заказ #<?= $order['order_id'] ?></h2>
            <button class="admin-modal-close">×</button>
        </div>
        <div class="admin-modal-body">
            <div class="order-info-grid">
                <div class="order-info-item">
                    <span class="info-label">Клиент:</span>
                    <span class="info-value"><?= htmlspecialchars($order['user_surname'] . ' ' . htmlspecialchars($order['user_name'])) ?></span>
                </div>
                <div class="order-info-item">
                    <span class="info-label">Телефон:</span>
                    <span class="info-value"><?= htmlspecialchars($order['user_phone']) ?></span>
                </div>
                <div class="order-info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= htmlspecialchars($order['user_email']) ?></span>
                </div>
                <div class="order-info-item">
                    <span class="info-label">Дата заказа:</span>
                    <span class="info-value"><?= date('d.m.Y H:i', strtotime($order['order_created_at'])) ?></span>
                </div>
                <div class="order-info-item">
                    <span class="info-label">Статус:</span>
                    <span class="info-value status-<?= mb_strtolower(str_replace(' ', '-', $order['order_status'])) ?>">
                        <?= htmlspecialchars($order['order_status']) ?>
                    </span>
                </div>
                <div class="order-info-item">
                    <span class="info-label">Сумма:</span>
                    <span class="info-value"><?= number_format($order['order_total_price'], 2, ',', ' ') ?> ₽</span>
                </div>
                <?php if (!empty($order['order_comment'])): ?>
                <div class="order-info-item full-width">
                    <span class="info-label">Комментарий:</span>
                    <span class="info-value"><?= htmlspecialchars($order['order_comment']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <h3 class="order-items-title">Состав заказа</h3>
            <div class="order-items-container">
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th class="product-col">Товар</th>
                            <th class="qty-col">Кол-во</th>
                            <th class="price-col">Цена</th>
                            <th class="total-col">Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="product-col">
                                    <div class="product-info">

                                        <span class="product-name"><?= htmlspecialchars($item['product_name']) ?></span>
                                    </div>
                                </td>
                                <td class="qty-col"><?= $item['order_item_quantity'] ?></td>
                                <td class="price-col"><?= number_format($item['order_item_price'], 2, ',', ' ') ?> ₽</td>
                                <td class="total-col"><?= number_format($item['order_item_quantity'] * $item['order_item_price'], 2, ',', ' ') ?> ₽</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="empty-items">Нет товаров в заказе</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr class="order-total-row">
                            <td colspan="3" class="total-label">Итого:</td>
                            <td class="total-value"><?= number_format($total_price, 2, ',', ' ') ?> ₽</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button class="btn btn-secondary close-popup">Закрыть</button>
        </div>
    </div>
</div>
<?php endif; ?>