<?php
require_once __DIR__ . '/../../database/connect.php';


$categoriesStmt = $pdo->query("SELECT category_id, category_name FROM categories WHERE parent_id IS NULL");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div id="categoryAddModal" class="admin-modal-overlay">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>Добавить категорию</h2>
            <button class="admin-modal-close">×</button>
        </div>
        <div class="admin-modal-body">
            <form id="addCategoryForm">
                <div class="form-group">
                    <label for="category_name">Название категории:</label>
                    <input type="text" name="category_name" id="category_name" required>
                </div>

                <div class="form-group">
                    <label for="category_description">Описание категории:</label>
                    <textarea name="category_description" id="category_description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="parent_category">Родительская категория:</label>
                    <select name="parent_category" id="parent_category">
                        <option value="">Без родителя</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                    <button type="button" class="btn btn-secondary close-popup">Закрыть</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="categoryEditModal" class="admin-modal-overlay">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>Редактировать категорию</h2>
            <button class="admin-modal-close">×</button>
        </div>
        <div class="admin-modal-body">
            <form id="editCategoryForm">
                <input type="hidden" name="category_id" id="edit_category_id">

                <div class="form-group">
                    <label for="edit_category_name">Название категории:</label>
                    <input type="text" name="category_name" id="edit_category_name" required>
                </div>

                <div class="form-group">
                    <label for="edit_category_description">Описание категории:</label>
                    <textarea name="category_description" id="edit_category_description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_parent_category">Родительская категория:</label>
                    <select name="parent_category" id="edit_parent_category">
                        <option value="">Без родителя</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <button type="button" class="btn btn-secondary close-popup">Закрыть</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="categoryDeleteModal" class="admin-modal-overlay">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h2>Удалить категорию</h2>
            <button class="admin-modal-close">×</button>
        </div>
        <div class="admin-modal-body">
            <p>Вы уверены, что хотите удалить эту категорию? Это действие нельзя отменить.</p>
            <form id="deleteCategoryForm">
                <input type="hidden" name="category_id" id="delete_category_id">
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Удалить</button>
                    <button type="button" class="btn btn-secondary close-popup">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</div>
