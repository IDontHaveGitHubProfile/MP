<?php
require_once '../database/connect.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

$userId = $_SESSION['user']['user_id'] ?? null;
$userEmail = null;
$userEmailVerified = 0;
$userPhone = null;
$hasPendingVerification = false;
$emailVerificationTokenExpires = null;
$userName = '';
$userSurname = '';

$deliveryCount = 0;
$favouriteCount = 0;
$reviewCount = 0;
$totalOrders = 0;
$totalItems = 0;

if ($userId) {
    $stmt = $pdo->prepare("SELECT user_name, user_surname, user_phone, user_email, user_email_verified, email_verification_token FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userName = $user['user_name'];
        $userSurname = $user['user_surname'];
        $userPhone = $user['user_phone'];
        $userEmail = $user['user_email'];
        $userEmailVerified = $user['user_email_verified'];
        $emailVerificationToken = $user['email_verification_token'];

        if (!empty($emailVerificationToken) && !$userEmailVerified) {
            $parts = explode('|', $emailVerificationToken);
            if (count($parts) === 2) {
                $expires = DateTime::createFromFormat('Y-m-d H:i:s', $parts[1]);
                if ($expires && $expires > new DateTime()) {
                    $hasPendingVerification = true;
                    $emailVerificationTokenExpires = $expires->format('Y-m-d H:i:s');
                }
            }
        }
    }


    $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND order_status = 'ожидает'");
    $stmt->execute([$userId]);
    $deliveryCount = (int)$stmt->fetchColumn();


    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favourites WHERE user_id = ?");
    $stmt->execute([$userId]);
    $favouriteCount = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ?");
    $stmt->execute([$userId]);
    $reviewCount = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT o.order_id) AS total_orders, SUM(oi.order_item_quantity) AS total_items
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = ?
");

    $stmt->execute([$userId]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalOrders = (int)$stats['total_orders'];
    $totalItems = (int)$stats['total_items'];
}
?>

<section class="container">
    <div class="flex profile-sides-cg ai-fs">
        <img src="../public/assets/profile-avatar.svg" alt="Аватар">
        <div class="flex fd-c profile-items-rg">
            <div class="flex ai-c title-cg">
                <p class="ff-usb dg3-text section-title">
                    <?= htmlspecialchars($userName . ' ' . $userSurname) ?>
                </p>
                <button id="editNameBtn" class="ff-usb ab-text underline">Изменить</button>
            </div>

            <div class="flex profile-items">
                <div class="flex fd-c profile-item">
                    <p class="subsection-title ff-um dg3-text">
                        <?php if ($hasPendingVerification): ?>
                            <span class="ar-text" style="color: orange;">&#33;</span>
                        <?php elseif (empty($userEmail)): ?>
                            <span class="ar-text" style="color: red;">&#33;</span>
                        <?php endif; ?>
                        Email
                    </p>
                    <?php if (!empty($userEmail) && $userEmailVerified): ?>
                        <p class="ff-ur dg3-text"><?= htmlspecialchars($userEmail); ?></p>
                    <?php elseif ($hasPendingVerification): ?>
                        <p class="ff-ur dg3-text">Ожидает подтверждения</p>
                    <?php else: ?>
                        <button id="profileMailBtn" class="ff-usb ab-text underline no-wrap">
                            <?= empty($userEmail) ? 'Добавить почту' : 'Подтвердите почту' ?>
                        </button>
                    <?php endif; ?>
                </div>

                <div class="flex fd-c profile-item">
                    <p class="subsection-title ff-um dg3-text">Телефон</p>
                    <p class="ff-ur dg3-text"><?= htmlspecialchars($userPhone); ?></p>
                </div>
            </div>

            <div class="flex jc-fs">
                <button id="profileLogOutBtn" class="ff-usb ar-text underline">Выйти из аккаунта</button>
            </div>
        </div>
    </div>
</section>

<section class="bg-lb indent-mt">
    <div class="container">
        <div class="hontainer-y">
            <div class="profile-grid">

                <a href="index.php?page=delivery" class="profile-block bg-w">
                    <div class="flex ai-fs jc-sb h-100">
                        <div class="flex fd-c js-sb h-100">
                            <div class="flex fd-c profile-block-rg">
                                <p class="subsection-title ff-usb dg3-text">Доставки</p>
                                <p class="medium-text ff-um dg3-text">Текущие заказы</p>
                            </div>
                            <p class="ff-ur dg3-text mt-auto as-fs ta-l"><?= $deliveryCount > 0 ? "$deliveryCount ожидается" : "Доставок не ожидается" ?></p>
                        </div>
                        <img src="../public/assets/profile-delivery.svg" alt="🚚">
                    </div>
                </a>

                <a href="index.php?page=favourite" class="profile-block bg-w">
                    <div class="flex ai-fs jc-sb h-100">
                        <div class="flex fd-c js-sb h-100">
                            <div class="flex fd-c profile-block-rg">
                                <p class="subsection-title ff-usb dg3-text">Избранное</p>
                                <p class="medium-text ff-um dg3-text">Товары, отложенные на потом</p>
                            </div>
                            <p class="ff-ur dg3-text mt-auto as-fs ta-l"><?= $favouriteCount ?> товаров</p>
                        </div>
                        <img src="../public/assets/profile-heart.svg" alt="🤍">
                    </div>
                </a>

                <a href="index.php?page=reviews" class="profile-block bg-w">
                    <div class="flex ai-fs jc-sb h-100">
                        <div class="flex fd-c js-sb h-100">
                            <div class="flex fd-c profile-block-rg">
                                <p class="subsection-title ff-usb dg3-text">Отзывы</p>
                                <p class="medium-text ff-um dg3-text">Поделитесь мнением о товаре</p>
                            </div>
                            <p class="ff-ur dg3-text mt-auto as-fs ta-l"><?= $reviewCount ?> отзывов</p>
                        </div>
                        <img src="../public/assets/profile-review.svg" alt="🗣">
                    </div>
                </a>

                <a href="index.php?page=orders" class="profile-block bg-w">
                    <div class="flex ai-fs jc-sb h-100">
                        <div class="flex fd-c js-sb h-100">
                            <div class="flex fd-c profile-block-rg">
                                <p class="subsection-title ff-usb dg3-text">Заказы</p>
                                <p class="medium-text ff-um dg3-text">Купленные товары</p>
                            </div>
                            <p class="ff-ur dg3-text mt-auto as-fs ta-l"><?= $totalOrders ?> заказов – <?= $totalItems ?> товаров</p>
                        </div>
                        <img src="../public/assets/profile-order.svg" alt="🛍">
                    </div>
                </a>

            </div>
        </div>
    </div>
</section>

<?php if ($hasPendingVerification && !empty($emailVerificationTokenExpires)): ?>
<script>
    const expiresAt = new Date('<?= $emailVerificationTokenExpires ?>');

    function checkTokenExpiration() {
        const now = new Date();
        if (now >= expiresAt) {
            const emailBlock = document.querySelector('.profile-item');
            const waitingText = emailBlock.querySelector('.ff-ur.dg3-text');
            const btnExists = emailBlock.querySelector('#profileMailBtn');

            if (waitingText && waitingText.textContent.includes('Ожидает подтверждения')) {
                waitingText.remove();
            }

            if (!btnExists) {
                const btn = document.createElement('button');
                btn.id = 'profileMailBtn';
                btn.className = 'ff-usb ab-text underline no-wrap';
                btn.textContent = 'Добавить почту';
                emailBlock.appendChild(btn);

                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (window.emailModalManager) {
                        emailModalManager.open(document.getElementById('emailAlertPopup'));
                    }
                });
            }

            clearInterval(intervalId);
        }
    }

    const intervalId = setInterval(checkTokenExpiration, 15000);
</script>
<?php endif; ?>
