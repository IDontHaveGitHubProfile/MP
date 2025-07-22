<?php
require '../database/connect.php';

$userId = $_SESSION['user']['user_id'] ?? 0;
$cartItems = [];
$email_verified = 0;

if ($userId > 0) {
    $stmt = $pdo->prepare("
        SELECT c.*, p.product_name, p.product_price, p.product_quantity,
               d.discount_value AS discount, d.discount_type,
               CASE 
                   WHEN d.discount_type = 'percentage' THEN p.product_price * (1 - d.discount_value / 100)
                   WHEN d.discount_type = 'amount' THEN p.product_price - d.discount_value
                   ELSE p.product_price
               END AS final_price
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        LEFT JOIN discount_product dp ON p.product_id = dp.product_id
        LEFT JOIN discounts d ON dp.discount_id = d.discount_id
            AND d.status = 'active'
            AND (d.start_date IS NULL OR d.start_date <= NOW())
            AND (d.end_date IS NULL OR d.end_date >= NOW())
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $emailStmt = $pdo->prepare("SELECT user_email_verified FROM users WHERE user_id = ?");
    $emailStmt->execute([$userId]);
    $email_verified = (int)$emailStmt->fetchColumn();
}

function format_price($price) {
    $formatted = number_format((float)$price, 2, ',', ' ');
    $formatted = preg_replace('/,00$/', '', $formatted);
    return $formatted;
}
?>

<section class="container indent-mt">
    <div id="cartWrapper" data-email-verified="<?= $email_verified ?>">
        <div class="empty-cart-message flex fd-c ai-c jc-c" id="emptyCartMessage" style="<?= empty($cartItems) ? '' : 'display:none;' ?>">
            <p class="dg3-text ff-usb average-text">Ваша корзина пуста</p>
        </div>

        <?php if (!empty($cartItems)): ?>
        <div class="flex jc-sb ai-fs cart-section">
            <div class="flex fd-c ai-fs carters-rg cart-items-col">
                <div class="flex fd-c cart-header">
                    <p class="section-title ff-usb dg3-text">Корзина</p>
                    <p class="subsection-title ff-ur dg3-text cart-count"></p>
                </div>

                <div class="flex jc-sb ai-c w-100 cart-controls">
                    <div class="flex ai-c select-all-box">
                        <label class="select-all-checkbox-wrapper">
                            <input type="checkbox" id="select-all" class="select-all-checkbox">
                            <span class="select-checkmark"></span>
                        </label>
                        <p class="dg3-text ff-ur select-all-text">Выбрать всё</p>
                    </div>
                    <button class="ar-text ff-ur cart-delete underline" id="deleteSelectedBtn">Удалить выбранное</button>
                </div>

                <div class="cart-products-rg flex fd-c" id="cartProducts">
                    <?php foreach ($cartItems as $item): ?>
                        <?php
                        $productId = (int)$item['product_id'];
                        $productName = htmlspecialchars($item['product_name']);
                        $productPrice = $item['product_price'] ?? 0;
                        $productQty = $item['cart_quantity'] ?? 1;
                        $productStock = (int)$item['product_quantity'];
                        $hasDiscount = !empty($item['discount']);
                        $finalPrice = $hasDiscount ? $item['final_price'] : $productPrice;
                        ?>
                        <div class="bg-lb h-100 cart-product flex jc-sb w-100"
                             data-product-id="<?= $productId ?>"
                             data-discount="<?= $hasDiscount ? '1' : '0' ?>"
                             data-final-price="<?= $finalPrice ?>"
                             data-original-price="<?= $productPrice ?>">
                            <div class="flex cart-general" style="column-gap: 1.5rem;">
                                <div class="cart-photo-wrap">
                                    <img class="cart-pimg" src="../public/assets/box.svg" alt="<?= $productName ?>">
                                    <label class="photo-checkbox-wrapper">
                                        <input type="checkbox" class="photo-checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>

                                <div class="flex fd-c jc-sb ai-fs product-info">
                                    <p class="cart-name ff-ur dg3-text"><?= $productName ?></p>
                                    <div class="cart-undername flex" style="column-gap: 0.75rem;">
                                        <button class="cart-underbtn remove-from-cart flex ai-c jc-c" data-id="<?= $productId ?>">
                                            <img src="../public/assets/cart-trashcan.svg" alt="Удалить">
                                        </button>
                                        <button class="cart-underbtn flex ai-c jc-c" data-product-id="<?= $productId ?>" data-favourite-btn>
                                            <img src="../public/assets/cart-heart-empty.svg" class="heart-icon cart-heart">
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex cart-numbers ai-fs" style="column-gap: 2rem;">
                                <div class="flex fd-c">
                                    <?php if ($hasDiscount): ?>
                                        <span class="ff-usb dg3-text cart-new-price"><?= format_price($finalPrice) ?> р.</span>
                                        <span class="ff-ur g2-text cart-old-price" style="text-decoration: line-through;">
                                            <?= format_price($productPrice) ?> р.</span>
                                    <?php else: ?>
                                        <span class="ff-usb dg3-text cart-new-price"><?= format_price($productPrice) ?> р.</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex fd-c quantity-rg">
                                    <div class="flex cart-quantity" style="column-gap: 0.5rem;">
                                        <button class="form-input bg-w flex jc-c ai-c quantity-controller minus"
                                                <?= $productQty <= 1 ? 'disabled' : '' ?>>&minus;</button>
                                        <input type="number"
                                               class="ff-ur g2-text form-input quantity-value"
                                               data-id="<?= $productId ?>"
                                               value="<?= (int)$productQty ?>"
                                               min="1"
                                               max="<?= $productStock ?>">
                                        <button class="form-input bg-w flex jc-c ai-c quantity-controller plus"
                                                <?= $productQty >= $productStock ? 'disabled' : '' ?>>+</button>
                                    </div>
                                    <p class="stock-text ff-ur dg3-text">В наличии <?= $productStock ?> шт.</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-lb flex fd-c cart-order order-summary-col" id="cartOrderBox">
                <p class="section-title corder-title ff-usb dg3-text">Заказ</p>
                <div class="flex fd-c corder-items">
                    <div class="flex jc-sb ai-c order-item medium-text">
                        <p class="dg3-text ff-ur corder-task">Скидка</p>
                        <p class="ar-text ff-ur corder-price discount">−0,00 р.</p>
                    </div>

                    <div class="flex jc-sb ai-c corder-total medium-text">
                        <p class="dg3-text ff-ur corder-task">Количество</p>
                        <p class="dg3-text ff-ur corder-price"><span id="selectedItemCount">0</span> шт.</p>
                    </div>

                    <div class="flex jc-sb ai-c corder-total average-text">
                        <p class="dg3-text ff-um corder-total-label">Итого</p>
                        <p class="dg3-text ff-um corder-total-value">0,00 р.</p>
                    </div>
                </div>

                <button id="cartOrderBtn"
                        class="button bg-dg3 dg3-pborder corder-btn ff-usb"
                        disabled
                        data-verified="<?= $email_verified ?>">
                    Оформить
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderBox = document.querySelector('.cart-order');
    let lastScrollPos = 0;
    let ticking = false;

    if (window.matchMedia('(max-width: 1024px)').matches) {
        window.addEventListener('scroll', function() {
            lastScrollPos = window.scrollY;
            
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    if (lastScrollPos > 100) {
                        orderBox.classList.add('hidden');
                    } else {
                        orderBox.classList.remove('hidden');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });
    }
});
</script>