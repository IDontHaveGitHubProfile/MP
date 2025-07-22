<?php
require_once '../database/connect.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

$query = $pdo->prepare("
    SELECT p.product_id, p.product_name, p.product_sku, p.product_quantity, p.product_price, 
        (SELECT ROUND(AVG(r.rating), 1) FROM reviews r WHERE r.product_id = p.product_id) AS avg_rating,
        (SELECT COUNT(*) FROM reviews r WHERE r.product_id = p.product_id) AS review_count
    FROM products p
    WHERE p.product_id = ?
");
$query->execute([$product_id]);
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p>Товар не найден</p>";
    exit;
}

// Проверяем, добавлен ли товар в корзину
$cart_query = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ? AND product_id = ?");
$cart_query->execute([$_SESSION['user']['user_id'] ?? 0, $product_id]);
$in_cart = $cart_query->fetchColumn() > 0;

// Проверяем, в избранном ли товар
$fav_stmt = $pdo->prepare("SELECT 1 FROM favourites WHERE user_id = ? AND product_id = ?");
$fav_stmt->execute([$_SESSION['user']['user_id'] ?? 0, $product_id]);
$in_favourites = (bool) $fav_stmt->fetch();
?>

<p class="section-title dg3-text ff-usb container bindent-mb">Товар</p>

<div class="bg-lb bindent-py bindent-mb">
    <div class="container">
        <div class="flex ai-fs product-sides">
            <!-- <div class="flex fd-c product-imgs">
                <div class="product-img-wrap">
                    <img src="../public/assets/banana.png" alt="<?= htmlspecialchars($product['product_name']); ?>" class="product-img">
                    <img class="product-heart" src="../public/assets/<?= $in_favourites ? 'product-heart-filled.svg' : 'product-heart-empty.svg'; ?>">
                </div>
                <div class="flex product-lilimgs">
                    <img src="../public/assets/banana.png" alt="Миниатюра товара 1" class="lilproduct-img">
                    <img src="../public/assets/banana.png" alt="Миниатюра товара 2" class="lilproduct-img">
                    <img src="../public/assets/banana.png" alt="Миниатюра товара 3" class="lilproduct-img">
                    <img src="../public/assets/banana.png" alt="Миниатюра товара 4" class="lilproduct-img">
                    <img src="../public/assets/banana.png" alt="Миниатюра товара 5" class="lilproduct-img">
                </div>
            </div> -->

            <div class="flex fd-c w-100">
                <p class="section-title dg3-text ff-usb product-name"><?= htmlspecialchars($product['product_name']); ?></p>
                <div class="flex ai-c product-rating">
                    <img src="../public/assets/star.svg" alt="Рейтинг">
                    <p class="average-text dg3-text ff-ur">
                        <span class="ff-usb"><?= $product['avg_rating'] ?? '0'; ?></span> - 
                        <a href="#productReviews-<?= $product['product_id'] ?>" class="ab-text underline"><?= $product['review_count']; ?> отзывов</a>
                    </p>
                </div>

                <div class="product-sku">
                    <div class="flex ai-c sku">
                        <p class="average-text dg3-text ff-ur">Артикул: 
                            <span class="ab-text underline copy-sku" data-sku="<?= htmlspecialchars($product['product_sku']); ?>">
                                <?= htmlspecialchars($product['product_sku']); ?>
                                <img src="../public/assets/copy.svg" class="copy-icon" alt="Копировать">
                                <img src="../public/assets/check.svg" class="check-icon" alt="Скопировано" style="display: none;">
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex jc-fs product-description">
                    <p class="average-text ab-text ff-ur underline">Описание</p>
                </div>

                <div class="flex ai-c jc-sb product-price">
                    <p class="section-title dg3-text ff-usb"><?= number_format($product['product_price'], 0, '', ' '); ?> р.</p>
                </div>

                <?php if ($product['product_quantity'] == 0): ?>
                    <button class="huge-text w-text ff-usb button product_cart-btn" disabled>
                        Нет в наличии
                    </button>
                <?php else: ?>
                    <?php if (!$in_cart): ?>
                        <button class="huge-text w-text ff-usb button product_cart-btn">
                            Добавить в корзину
                        </button>
                    <?php else: ?>
                        <div class="flex jc-sb ai-c product-btns">
                            <button class="average-text dg3-text ff-usb product-btn w-100 dg3-pborder">
                                Перейти к корзине
                            </button>
                            <button class="average-text w-text bg-dgray3 dg3-pborder ff-usb button product-btn w-100">
                                Убрать из корзины
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



