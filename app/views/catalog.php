<?php

require_once '../database/connect.php';

$inCart = [];

if (isset($_SESSION['user']['user_id'])) {
    $userId = $_SESSION['user']['user_id'];
    $stmt = $pdo->prepare("SELECT product_id FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    $inCart = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

try {
    $query = "SELECT FLOOR(MIN(product_price)) AS min_price, CEIL(MAX(product_price)) AS max_price FROM products";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $prices = $stmt->fetch(PDO::FETCH_ASSOC);
    $minPrice = $prices['min_price'] ?? 0;
    $maxPrice = $prices['max_price'] ?? 1000;
} catch (PDOException $e) {
    die("Ошибка запроса: " . $e->getMessage());
}


$selectedCategoryName = 'Все категории';
if (isset($_GET['category_id'])) {
    $categoryId = (int)$_GET['category_id'];
    $stmt = $pdo->prepare("SELECT category_name FROM categories WHERE category_id = ?");
    $stmt->execute([$categoryId]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($category) {
        $selectedCategoryName = $category['category_name'];
    }
}

require("../app/modules/banner.php"); 
?>

<section class="container indent-mt">
    <div class="flex ai-c jc-fe">
        <p class="ff-ur small-text dg3-text"></p>
    </div>
    <div class="flex jc-sb ai-fs catalog-cg">
        <!-- Фильтр -->
        <div class="bg-lb catalog-filter sticky-side">
            <div class="flex fd-c catalog-filter-inner">
                <!-- <div class="flex fd-c catalog-filter-section">
                    <p class="ff-um dg3-text small-text">Категория</p>
                    <button class="ff-ur g2-text small-text filter-input catalog-dropdown ta-l" id="catalogCategoriesBtn" 
                            title="<?= htmlspecialchars($selectedCategoryName) ?>">
                        <?= htmlspecialchars($selectedCategoryName) ?>
                    </button>
                    <div class="flex fd-c subcategories-filter" id="subcategoriesContainer">
                        <?php
                        try {
                            $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
                            if ($categoryId) {
                                // Получаем все подкатегории (включая подподкатегории) для выбранной категории
                                $query = "WITH RECURSIVE category_tree AS (
                                            SELECT category_id, category_name, parent_id FROM categories WHERE category_id = :category_id
                                            UNION ALL
                                            SELECT c.category_id, c.category_name, c.parent_id FROM categories c
                                            JOIN category_tree ct ON c.parent_id = ct.category_id
                                          )
                                          SELECT * FROM category_tree WHERE category_id != :category_id ORDER BY category_name ASC";
                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
                                $stmt->execute();
                                $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($subcategories) {
                                    $limit = 6;
                                    foreach ($subcategories as $i => $subcategory) {
                                        $subcategoryId = $subcategory['category_id'];
                                        $subcategoryName = $subcategory['category_name'];
                                        $hiddenClass = ($i >= $limit) ? " hidden" : "";
                                        echo "<div class='flex ai-fs subcategory catalog-checkbox-wrapper$hiddenClass'>
                                                <input type='checkbox' id='subcategory_$subcategoryId' 
                                                       class='catalog-checkbox' checked>
                                                <label for='subcategory_$subcategoryId' class='ff-ur dg3-text small-text'>
                                                    " . htmlspecialchars($subcategoryName) . "
                                                </label>
                                              </div>";
                                    }

                                    if (count($subcategories) > $limit) {
                                        echo '<div class="flex ai-fs" id="toggleCategoriesWrap">
                                                <button class="ff-usb ab-text small-text underline" id="toggleCategoriesBtn">Показать все</button>
                                              </div>';
                                    }
                                }
                            }
                        } catch (PDOException $e) {
                            error_log("Ошибка запроса подкатегорий: " . $e->getMessage());
                        }
                        ?>
                    </div>
                </div> -->

                <div class="flex fd-c catalog-filter-section">
                    <p class="ff-um dg3-text small-text">Цена</p>
                    <div class="flex ai-c jc-sb filter-range-cg">
                        <div class="flex ai-c">
                            <input type="text" id="min-price" class="ff-ur filter-input g2-text small-text"
                            placeholder="<?= number_format($minPrice, 0, '', ' ') ?>">
                        </div>
                        <div class="flex ai-c">
                            <input type="text" id="max-price" class="ff-ur filter-input g2-text small-text"
                            placeholder="<?= number_format($maxPrice, 0, '', ' ') ?>">
                        </div>
                    </div>
                    <div class="range-slider-container">
                        <div class="slider-track"></div>
                        <input type="range" id="min-range" class="filter-range"
                            min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= $minPrice ?>">
                        <input type="range" id="max-range" class="filter-range"
                            min="<?= $minPrice ?>" max="<?= $maxPrice ?>" value="<?= $maxPrice ?>">
                    </div>
                </div>

                <div class="catalog-filter-section">
                    <div class="flex jc-sb ai-c toggle-wrapper">
                        <p class="ff-um dg3-text small-text">В наличии</p>
                        <label id="show-quantity" class="toggle-switch">
                            <input class="input-switch" type="checkbox" id="show-quantity" name="in_stock">
                            <span class="switch"></span>
                        </label>
                    </div>
                </div>

                <div class="catalog-filter-section">
                    <div class="flex jc-sb ai-c toggle-wrapper">
                        <p class="ff-um dg3-text small-text">Со скидкой</p>
                        <label class="toggle-switch">
                            <input class="input-switch" type="checkbox" id="sale-toggle">
                            <span class="switch"></span>
                        </label>
                    </div>
                </div>

                <div class="catalog-filter-section">
                    <button class="ff-um w-text small-text button bg-ab ab-bborder filter-btn w-100">Очистить</button>
                </div>
            </div>
        </div>

        <!-- Каталог -->
        <div class="bg-lb bindent-p w-100 catalog-grid" id="productContainer">
            
        </div>
    </div>
</section>