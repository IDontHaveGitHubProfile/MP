<?php
require_once '../database/connect.php';

$query = "SELECT category_id, category_name FROM categories ORDER BY category_name";
$stmt = $pdo->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div id="admin-product-new_modal" class="popup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h2>Добавить товар</h2>
            <span class="popup-x">×</span>
        </div>
        <form id="productForm">
            <div class="form-group">
                <label for="productName">Название товара</label>
                <input type="text" id="productName" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="category">Категория</label>
                <select id="category" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['category_id']) ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Цена</label>
                <input type="number" id="price" name="product_price" required step="0.01" min="0">
            </div>
            <div class="form-group">
                <label for="quantity">Количество</label>
                <input type="number" id="quantity" name="product_quantity" required min="0">
            </div>
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea id="description" name="product_description" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Добавить товар</button>
            </div>
        </form>
    </div>
</div>


<div id="admin-product-edit_modal" class="popup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h2>Редактировать товар</h2>
            <span class="popup-x">×</span>
        </div>
        <form id="editProductForm">
            <input type="hidden" id="editProductId" name="product_id">
            <div class="form-group">
                <label for="editProductName">Название товара</label>
                <input type="text" id="editProductName" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="editCategory">Категория</label>
                <select id="editCategory" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['category_id']) ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="editPrice">Цена</label>
                <input type="number" id="editPrice" name="product_price" required step="0.01" min="0">
            </div>
            <div class="form-group">
                <label for="editQuantity">Количество</label>
                <input type="number" id="editQuantity" name="product_quantity" required min="0">
            </div>
            <div class="form-group">
                <label for="editDescription">Описание</label>
                <textarea id="editDescription" name="product_description" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </form>
    </div>
</div>

<div id="admin-product-delete_modal" class="popup" style="display: none;">
    <div class="popup-content">
        <div class="popup-header">
            <h2>Удалить товар</h2>
            <span class="popup-x">×</span>
        </div>
        <p>Вы уверены, что хотите удалить этот товар?</p>
        <input type="hidden" id="deleteProductId">
        <div class="form-group">
            <button id="confirmDelete" class="btn btn-danger">Удалить</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('admin-product-delete_modal')">Отменить</button>
        </div>
    </div>
</div>