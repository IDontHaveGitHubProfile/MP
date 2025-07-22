<?php

require_once '../database/connect.php';

$minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 10000;
$inStock = isset($_GET['in_stock']) && $_GET['in_stock'] == 1;
$onSale = isset($_GET['sale']) && $_GET['sale'] == 1;
$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$selectedSubcategories = isset($_GET['subcategories']) && is_array($_GET['subcategories']) ? array_map('intval', $_GET['subcategories']) : [];

$inCart = $inFavourites = [];
if (isset($_SESSION['user']['user_id'])) {
    $userId = $_SESSION['user']['user_id'];


    $stmt = $pdo->prepare("SELECT product_id FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    $inCart = $stmt->fetchAll(PDO::FETCH_COLUMN);


    $stmt = $pdo->prepare("SELECT product_id FROM favourites WHERE user_id = ?");
    $stmt->execute([$userId]);
    $inFavourites = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$where = "p.product_price BETWEEN :min_price AND :max_price";
$params = [
    ':min_price' => $minPrice,
    ':max_price' => $maxPrice
];

if ($inStock) {
    $where .= " AND p.product_quantity > 0";
}

if ($onSale) {
    $where .= " AND d.discount_value IS NOT NULL";
}

if ($categoryId) {
    $where .= " AND (p.category_id = :category_id OR p.category_id IN (
                WITH RECURSIVE category_tree AS (
                    SELECT category_id FROM categories WHERE category_id = :category_id_root
                    UNION ALL
                    SELECT c.category_id FROM categories c
                    JOIN category_tree ct ON c.parent_id = ct.category_id
                )
                SELECT category_id FROM category_tree WHERE category_id != :category_id_child
            ))";
    $params[':category_id'] = $categoryId;
    $params[':category_id_root'] = $categoryId;
    $params[':category_id_child'] = $categoryId;
}

if (!empty($selectedSubcategories)) {
    $where .= " AND p.category_id IN (" . implode(',', $selectedSubcategories) . ")";
}

$query = "
    SELECT p.*, d.discount_value AS discount, d.discount_type,
    CASE
        WHEN d.discount_type = 'percentage' THEN p.product_price * (1 - d.discount_value / 100)
        WHEN d.discount_type = 'amount' THEN p.product_price - d.discount_value
        ELSE p.product_price
    END AS final_price,
    COALESCE(AVG(r.rating), 0) AS product_rating, COUNT(r.review_id) AS review_count
    FROM products p
    LEFT JOIN discount_product dp ON p.product_id = dp.product_id
    LEFT JOIN discounts d ON dp.discount_id = d.discount_id AND d.status = 'active'
    AND (d.start_date IS NULL OR d.start_date <= NOW())
    AND (d.end_date IS NULL OR d.end_date >= NOW())
    LEFT JOIN reviews r ON p.product_id = r.product_id
    WHERE $where
    GROUP BY p.product_id
    ORDER BY final_price ASC
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

function getReviewWord($n) {
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return 'отзывов';
    if ($n1 > 1 && $n1 < 5) return 'отзыва';
    if ($n1 == 1) return 'отзыв';
    return 'отзывов';
}

foreach ($products as $product):
    $hasDiscount = !empty($product['discount']);
    $finalPrice = $hasDiscount ? $product['final_price'] : $product['product_price'];
    $avgRating = $product['product_rating'] ? number_format($product['product_rating'], 1) : '0';
    $avgRating = $avgRating === '0.0' ? '0' : $avgRating;
    $reviewCount = (int)$product['review_count'];
    $reviewWord = getReviewWord($reviewCount);

    $productId = $product['product_id'];
    $isFavourite = in_array($productId, $inFavourites);
?>
<div class="product-card flex fd-c"
    data-product-id="<?= $productId ?>"
    data-category-id="<?= $product['category_id'] ?>"
    data-price="<?= $product['product_price'] ?>"
    data-sale="<?= $hasDiscount ? '1' : '0' ?>"
    data-rating="<?= $avgRating ?>">
    <div class="img-wrap flex jc-c">
        <button class="heart-btn" data-favourite-btn data-product-id="<?= $productId ?>">
            <img src="../public/assets/catalog-heart-<?= $isFavourite ? 'filled' : 'empty' ?>.svg" class="heart-icon catalog-heart" alt="<?= $isFavourite ? '♥' : '🤍' ?>">
           
        </button>
        <img src="../public/assets/box.svg" alt="<?= htmlspecialchars($product['product_name']) ?>" class="catalog-img">
    </div>
    <p class="card-name ff-ur dg3-text"><?= htmlspecialchars($product['product_name']) ?></p>

    <div class="flex ai-c sku small-text">
        <p class="ff-ur dg3-text">Артикул:
            <button class="ab-text underline copy-sku" data-sku="<?= htmlspecialchars($product['product_sku']) ?>">
                <?= htmlspecialchars($product['product_sku']) ?>
                <img src="../public/assets/copy.svg" class="copy-icon">
                <img src="../public/assets/check.svg" class="check-icon" style="display: none;">
            </button>
        </p>
    </div>
    <div class="price-container">
        <div class="price-line">
            <span class="ff-ur dg3-text current-price"><?= number_format($finalPrice, 0, '', ' ') ?> ₽</span>
            <?php if ($hasDiscount): ?>
                <span class="ff-ur old-price"><?= number_format($product['product_price'], 0, '', ' ') ?> ₽</span>
            <?php endif; ?>
        </div>
    </div>
    <?php if (in_array($productId, $inCart)): ?>
        <a href="index.php?page=cart" class="cart-btn go-to-cart-btn ff-um cart-btn-added ta-c"
           data-product-id="<?= $productId ?>">Корзине</a>
    <?php else: ?>
        <button class="cart-btn add-to-cart-btn ff-um <?= $product['product_quantity'] <= 0 ? 'cart-btn-quantity' : 'button' ?>"
            data-product-id="<?= $productId ?>" <?= $product['product_quantity'] <= 0 ? 'disabled' : '' ?>>
            <?= $product['product_quantity'] <= 0 ? 'Не в наличии' : 'В корзину' ?>
        </button>
    <?php endif; ?>
</div>
<?php endforeach; ?>
