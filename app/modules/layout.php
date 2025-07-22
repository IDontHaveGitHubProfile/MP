<?php
require_once '../database/connect.php';

$email_verified = 0;

$isAdminPage = isset($page) && strpos($page, 'admin_') === 0 && $page !== 'admin_form';
$isAdminForm = isset($page) && $page === 'admin_form';
$is404 = isset($page) && $page === '404';

$showEmailSuccess = false;
if (isset($_SESSION['user']) && !empty($_SESSION['show_email_success'])) {
    $user_id = $_SESSION['user']['user_id'] ?? null;
    if ($user_id) {
        $stmt = $pdo->prepare("SELECT user_email_verified FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user['user_email_verified'] == 1) {
            $showEmailSuccess = true;
            unset($_SESSION['show_email_success']);
        }
    }
}

$showOrderSuccess = false;
if (isset($_SESSION['show_order_success'])) {
    $showOrderSuccess = true;
    unset($_SESSION['show_order_success']);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?= htmlspecialchars(isset($metaKeywords) ? $metaKeywords : 'мотозапчасти, мото магазин') ?>">
    <meta name="description" content="<?= htmlspecialchars(isset($metaDescription) ? $metaDescription : 'Интернет-магазин мотозапчастей') ?>">
    <title><?= htmlspecialchars((isset($disableTitleSuffix) && $disableTitleSuffix) ? $title : $title . ' | Мото-Профи') ?></title>

    <?php if ($isAdminPage || $isAdminForm): ?>

        <link rel="apple-touch-icon" href="../public/admin-favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="../public/admin-favicon/favicon-96x96.png">
        <link rel="manifest" href="../public/admin-favicon/site.webmanifest">
        <meta name="theme-color" content="#30a6e1">
    <?php else: ?>

        <link rel="apple-touch-icon" href="../public/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="../public/favicon/favicon-96x96.png">
        <link rel="manifest" href="../public/favicon/site.webmanifest">
        <meta name="theme-color" content="#ffffff">
    <?php endif; ?>

    <?php if (!$isAdminPage && !$isAdminForm && !$is404): ?>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script>
            if (typeof jQuery === 'undefined') {
                let script = document.createElement('script');
                script.src = '../public/libs/jQuery/jquery-3.6.0.min.js';
                document.head.appendChild(script);
            }
            if (typeof Inputmask === 'undefined') {
                let script = document.createElement('script');
                script.src = '../public/libs/inputmask/inputmask.min.js';
                document.head.appendChild(script);
            }
        </script>
        <link rel="stylesheet" href="../public/css/style.css">
        <link rel="stylesheet" href="../public/css/typography.css">
        <link rel="stylesheet" href="../public/css/variables.css">
    <?php elseif ($isAdminForm): ?>

        <link rel="stylesheet" href="../public/css/admin.css">
        <link rel="stylesheet" href="../public/css/typography.css">
        <link rel="stylesheet" href="../public/css/variables.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="../public/js/adminValidate.js" defer></script>
    <?php elseif ($isAdminPage): ?>

        <link rel="stylesheet" href="../public/css/admin-main.css">
        <link rel="stylesheet" href="../public/css/typography.css">
        <link rel="stylesheet" href="../public/css/variables.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
                <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <?php endif; ?>
</head>

<body class="<?= $isAdminPage ? 'admin-main-body' : ($isAdminForm ? 'admin-form-body' : '') ?>">

<?php if ($isAdminPage && $page === 'admin_products'): ?>
    <?php include __DIR__ . '/../modules/admin-product_modal.php'; ?>
    <script src="../public/js/adminProductModal.js" defer></script>
<?php endif; ?>

<?php if ($isAdminPage && $page === 'admin_orders'): ?>
    <script src="../public/js/adminOrderModal.js" defer></script>
<?php endif; ?>

<?php if (!$isAdminPage && !$isAdminForm && !$is404): ?>
    <?php include __DIR__ . '/header.php'; ?>
<?php endif; ?>

<main class="main-content <?= $isAdminPage ? 'admin-main-content' : '' ?>">
    <?php if (!$isAdminPage && !$isAdminForm && !$is404): ?>
        <button id="scrollToTop" class="scroll-top ff-usb" aria-label="Наверх">↑</button>
    <?php endif; ?>

    <?php if (isset($content)) include $content; ?>
</main>

<?php if (!$isAdminPage && !$isAdminForm && !$is404): ?>
    <?php include __DIR__ . '/footer.php'; ?>
    <?php require __DIR__ . '/../modules/exit_modal.php'; ?>

    <?php if ($page === 'profile'): ?>
        <?php 
        $orderData = $_SESSION['order_success'] ?? null;
        $showOrderSuccess = !empty($orderData);
        ?>

        <?php if ($showOrderSuccess): ?>
            <?php require __DIR__ . '/../modules/order_modal.php'; ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const popup = document.getElementById('orderPopup');
                if (popup) {
                    console.log('Order popup found, showing...');
                    popup.classList.add('active');

                    const closeHandler = () => {
                        popup.classList.remove('active');
                        fetch('/mark-order-viewed.php?order_id=<?= $orderData['order_id'] ?>')
                            .then(response => response.json())
                            .then(data => console.log('Order marked as viewed'))
                            .catch(err => console.error('Error:', err));
                    };

                    document.querySelectorAll('.popup-x, .popup-secondary').forEach(btn => {
                        btn.addEventListener('click', closeHandler);
                    });

                    popup.addEventListener('click', function(e) {
                        if (e.target === this) closeHandler();
                    });
                }
            });
            </script>
            <?php unset($_SESSION['order_success']); ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['user_id'])): ?>
        <?php
        $user_id = $_SESSION['user']['user_id'];
        $stmt = $pdo->prepare("SELECT user_email_verified FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $email_verified = $user['user_email_verified'] ?? 0;
        ?>

        <?php if ($email_verified == 0 && in_array($page, ['profile', 'cart', 'contacts'])): ?>
            <?php require __DIR__ . '/../modules/email_modal.php'; ?>
            <?php require __DIR__ . '/../modules/email-repeat_modal.php'; ?>
        <?php endif; ?>

        <?php if ($showEmailSuccess): ?>
            <?php require __DIR__ . '/../modules/email-success_modal.php'; ?>
        <?php endif; ?>

        <?php if ($page === 'contacts' && $email_verified == 1): ?>
            <?php require __DIR__ . '/../modules/contacts_modal.php'; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php require __DIR__ . '/../modules/reg_modal.php'; ?>
        <?php if (in_array($page, ['catalog', 'contacts'])): ?>
            <?php require __DIR__ . '/../modules/guest_modal.php'; ?>
        <?php endif; ?>
    <?php endif; ?>

    <script src="../public/js/tabOutline.js" defer></script>
    <script src="../public/js/scrollToTop.js" defer></script>
    <script src="../public/js/stickyHeader.js" defer></script>
    <script src="../public/js/accordionArrow.js" defer></script>
    <script src="../public/js/accordionSidebar.js" defer></script>

    <?php
    $stmt = $pdo->query("SELECT category_id, parent_id, category_name FROM categories ORDER BY category_name");
    $allCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    function buildCategoryTree(array $categories, $parentId = null) {
        $branch = [];
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $children = buildCategoryTree($categories, $category['category_id']);
                if ($children) {
                    $category['children'] = $children;
                }
                $branch[] = $category;
            }
        }
        return $branch;
    }

    $categoryTree = buildCategoryTree($allCategories);
    ?>
    <script>
        const categoryTree = <?= json_encode($categoryTree, JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <script src="../public/js/categoryCatalogSidebar.js" defer></script>

    <?php if ($page === 'catalog'): ?>
        <script src="../public/js/priceSlider.js" defer></script>
        <script src="../public/js/stockUpdater.js" defer></script>
        <script src="../public/js/switchClicker.js" defer></script>
        <script src="../public/js/catalogFilter.js" defer></script>
    <?php endif; ?>

    <?php if (in_array($page, ['catalog', 'product', 'favourite'])): ?>
        <script src="../public/js/copySku.js" defer></script>
        <script src="../public/js/tooltip.js" defer></script>
    <?php endif; ?>

    <?php if ($page === 'home'): ?>
        <script src="../public/js/popularCategoryName.js" defer></script>
    <?php endif; ?>

    <?php if (in_array($page, ['signup', 'login'])): ?>
        <script src="../public/js/clearPhone.js" defer></script>
        <script src="../public/js/eyePasswordForm.js" defer></script>
    <?php endif; ?>

    <?php if ($page === 'login'): ?>
        <script src="../public/js/authValidate.js" defer></script>
    <?php endif; ?>

    <?php if ($page === 'signup'): ?>
        <script src="../public/js/regValidate.js" defer></script>
    <?php endif; ?>

    <?php if (isset($_SESSION['user'])): ?>
        <script src="../public/js/headerProfileMenu.js" defer></script>
        <script src="../public/js/cartCounter.js" defer></script>
        <script src="../public/js/favouriteCounter.js" defer></script>
        <script src="../public/js/randomContact.js" defer></script>
        <script src="../public/js/logOutModal.js" defer></script>

        <?php if (in_array($page, ['catalog', 'cart'])): ?>
            <script src="../public/js/catalogSync.js" defer></script>
        <?php endif; ?>

        <?php if ($page === 'cart'): ?>
            <script src="../public/js/cartOrder.js" defer></script>
        <?php endif; ?>

        <?php if ($page === 'cart' && $email_verified == 1): ?>
            <script src="../public/js/orderModal.js" defer></script>
        <?php endif; ?>

        <?php if ($page === 'favourite'): ?>
            <script src="../public/js/favouriteFilter.js" defer></script>
        <?php endif; ?>

        <?php if ($page === 'profile'): ?>
            <script src="../public/js/editNameModal.js" defer></script>
            <script src="../public/js/editPasswordModal.js" defer></script>
            <?php require __DIR__ . '/../modules/name_modal.php'; ?>
        <?php endif; ?>

        <?php if ($email_verified == 0 && in_array($page, ['profile', 'cart', 'contacts'])): ?>
            <script src="../public/js/emailVerifyModal.js" defer></script>
            <script src="../public/js/emailRepeatModal.js" defer></script>
        <?php endif; ?>

        <?php if ($showEmailSuccess): ?>
            <script src="../public/js/emailSuccessModal.js" defer></script>
        <?php endif; ?>

        <?php if ($page === 'contacts' && $email_verified == 1): ?>
            <script src="../public/js/contactsModal.js" defer></script>
        <?php endif; ?>
    <?php else: ?>
        <script src="../public/js/regModal.js" defer></script>
        <?php if (in_array($page, ['catalog', 'contacts'])): ?>
            <script src="../public/js/guestAlertModal.js" defer></script>
        <?php endif; ?>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (sessionStorage.getItem('showEmailSuccessModal') === '1') {
            sessionStorage.removeItem('showEmailSuccessModal');

            if (!document.getElementById('emailSuccessPopup')) {
                fetch('../app/modules/email-success_modal.php')
                    .then(response => response.text())
                    .then(html => {
                        document.body.insertAdjacentHTML('beforeend', html);

                        if (typeof EmailSuccessModal !== 'undefined') {
                            new EmailSuccessModal();
                        } else {
                            let script = document.createElement('script');
                            script.src = '../public/js/emailSuccessModal.js';
                            script.onload = function () {
                                new EmailSuccessModal();
                            };
                            document.body.appendChild(script);
                        }
                    });
            } else if (typeof EmailSuccessModal !== 'undefined') {
                new EmailSuccessModal();
            }
        }
    });

    </script>
    <?php endif; ?>
</body>
</html>