<?php
require_once '../database/connect.php'; // в connect.php уже session_start()

header('Content-Type: text/html; charset=utf-8');

if (!isset($_SESSION['user']['user_id'])) {

    echo '';
    exit;
}

$userId = (int)$_SESSION['user']['user_id'];
$inStock = isset($_GET['in_stock']) && $_GET['in_stock'] === '1';
$sort = $_GET['sort'] ?? 'recent';

$validSorts = ['recent', 'old', 'cheap', 'expensive'];
if (!in_array($sort, $validSorts, true)) {
    $sort = 'recent';
}


$stmt = $pdo->prepare("SELECT product_id FROM cart WHERE user_id = ?");
$stmt->execute([$userId]);
$inCart = $stmt->fetchAll(PDO::FETCH_COLUMN);


$stmt = $pdo->prepare("SELECT product_id FROM favourites WHERE user_id = ?");
$stmt->execute([$userId]);
$inFavourites = $stmt->fetchAll(PDO::FETCH_COLUMN);


$where = "f.user_id = :user_id";
$params = [':user_id' => $userId];

if ($inStock) {
    $where .= " AND p.product_quantity > 0";
}

switch ($sort) {
    case 'cheap':
        $orderBy = "final_price ASC";
        break;
    case 'expensive':
        $orderBy = "final_price DESC";
        break;
    case 'old':
        $orderBy = "f.favourite_added_to ASC";
        break;
    default:
        $orderBy = "f.favourite_added_to DESC";
        break;
}

$query = "
    SELECT 
        p.*, 
        d.discount_value AS discount, 
        d.discount_type,
        CASE
            WHEN d.discount_type = 'percentage' THEN p.product_price * (1 - d.discount_value / 100)
            WHEN d.discount_type = 'amount' THEN p.product_price - d.discount_value
            ELSE p.product_price
        END AS final_price,
        COALESCE(AVG(r.rating), 0) AS product_rating,
        COUNT(r.review_id) AS review_count
    FROM favourites f
    JOIN products p ON f.product_id = p.product_id
    LEFT JOIN discount_product dp ON p.product_id = dp.product_id
    LEFT JOIN discounts d ON dp.discount_id = d.discount_id
        AND d.status = 'active'
        AND (d.start_date IS NULL OR d.start_date <= NOW())
        AND (d.end_date IS NULL OR d.end_date >= NOW())
    LEFT JOIN reviews r ON p.product_id = r.product_id
    WHERE $where
    GROUP BY p.product_id
    ORDER BY $orderBy
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

function getReviewWord(int $n): string {
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return 'отзывов';
    if ($n1 > 1 && $n1 < 5) return 'отзыва';
    if ($n1 === 1) return 'отзыв';
    return 'отзывов';
}

if (empty($products)) {

    echo '';
    exit;
}

foreach ($products as $product):
    $productId = (int)$product['product_id'];
    $isFavourite = in_array($productId, $inFavourites, true);
    $isInCart = in_array($productId, $inCart, true);
    $hasDiscount = !empty($product['discount']);
    $finalPrice = $hasDiscount ? $product['final_price'] : $product['product_price'];
    $avgRatingRaw = (float)$product['product_rating'];
    $avgRating = $avgRatingRaw === 0.0 ? '0' : number_format($avgRatingRaw, 1);
    $reviewCount = (int)$product['review_count'];
    $reviewWord = getReviewWord($reviewCount);
    ?>
    <div class="product-card flex fd-c"
         data-product-id="<?= $productId ?>"
         data-category-id="<?= (int)$product['category_id'] ?>"
         data-price="<?= (float)$product['product_price'] ?>"
         data-sale="<?= $hasDiscount ? '1' : '0' ?>"
         data-rating="<?= htmlspecialchars($avgRating) ?>">
        <div class="img-wrap flex jc-c">
            <button class="heart-btn" data-favourite-btn data-product-id="<?= $productId ?>">
                <img src="../public/assets/catalog-heart-<?= $isFavourite ? 'filled' : 'empty' ?>.svg"
                     class="heart-icon catalog-heart" alt="<?= $isFavourite ? '♥' : '🤍' ?>">
            </button>
            <img src="<?= htmlspecialchars($product['product_image'] ?? '../public/assets/box.svg') ?>"
                 alt="<?= htmlspecialchars($product['product_name']) ?>" class="catalog-img">
        </div>
        <p class="card-name ff-ur dg3-text"><?= htmlspecialchars($product['product_name']) ?></p>
        <div class="flex ai-c catalog-rate small-text">
            <img src="../public/assets/star.svg" class="catalog-star" alt="звезда">
            <p class="ff-ur dg3-text"><?= $avgRating ?></p>
            <p class="ff-ur dg3-text">-</p>
            <a class="ff-ur ab-text underline" href="product.php?product_id=<?= $productId ?>" target="_blank"
               onclick="sessionStorage.setItem('scrollToReview', '#productReviews-<?= $productId ?>')">
                <?= $reviewCount ?> <?= $reviewWord ?>
            </a>
        </div>
        <div class="flex ai-c sku small-text">
            <p class="ff-ur dg3-text">Артикул:
                <button class="ab-text underline copy-sku" data-sku="<?= htmlspecialchars($product['product_sku']) ?>">
                    <?= htmlspecialchars($product['product_sku']) ?>
                    <img src="../public/assets/copy.svg" class="copy-icon" alt="копировать">
                    <img src="../public/assets/check.svg" class="check-icon" style="display: none;" alt="скопировано">
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
        <?php if ($isInCart): ?>
            <a href="index.php?page=cart" class="cart-btn go-to-cart-btn ff-um cart-btn-added ta-c"
               data-product-id="<?= $productId ?>">В корзине</a>
        <?php else: ?>
            <button class="cart-btn add-to-cart-btn ff-um <?= $product['product_quantity'] <= 0 ? 'cart-btn-quantity' : 'button' ?>"
                    data-product-id="<?= $productId ?>" <?= $product['product_quantity'] <= 0 ? 'disabled' : '' ?>>
                <?= $product['product_quantity'] <= 0 ? 'Не в наличии' : 'В корзину' ?>
            </button>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
