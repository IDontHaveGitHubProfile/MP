<?php

require_once __DIR__ . '/../database/connect.php';

// Определение запрашиваемой страницы
$page = basename($_GET['page'] ?? 'home');

// Настройки доступа
$protected_pages = ['profile', 'cart', 'favourite'];
$admin_only_pages = ['admin_dashboard', 'admin_products', 'admin_users', 'admin_orders', 'admin_reviews', 'admin_categories', 'admin_delivery', 'admin_discounts', 'admin_settings'];
$auth_forbidden_pages = ['login', 'signup', 'admin_form'];

// Проверка доступа
if (in_array($page, $auth_forbidden_pages) && isset($_SESSION['user'])) {
    header('Location: index.php?page=' . (!empty($_SESSION['user']['admin']) ? 'admin_dashboard' : 'profile'));
    exit;
}

if (in_array($page, $protected_pages) && !isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

if (in_array($page, $admin_only_pages) && (empty($_SESSION['user']['admin']) || !isset($_SESSION['user']))) {
    header('Location: index.php?page=home');
    exit;
}

// Разрешенные страницы
$allowed_pages = [
    'home', 'catalog', 'contacts',
    'login', 'signup', 'profile', 'cart', 'favourite', 'orders',
    'admin_form', 'admin_dashboard', 'verificator', 'admin_products', 'admin_users', 'admin_orders', 'admin_reviews', 'admin_categories', 'admin_delivery', 'admin_discounts', 'admin_settings'
];

// Если страница не разрешена, то показываем 404
if (!in_array($page, $allowed_pages)) {
    $page = '404';
}

// Путь к файлу контента
$content = __DIR__ . "/../app/views/{$page}.php";

// Если файл не существует, показываем 404
if (!file_exists($content)) {
    $content = __DIR__ . '/../app/views/404.php';
}



switch ($page) {
    case 'home':
        $title = 'Мото-Профи – интернет-магазин запчастей для мотоциклов, скутеров и квадроциклов в Ижевске';
        $disableTitleSuffix = true;
        $metaKeywords = 'мотозапчасти, купить запчасти для мотоциклов, запчасти для скутеров, квадроциклы запчасти, мото магазин Ижевск, оригинальные мотозапчасти';
        $metaDescription = 'Интернет-магазин мотозапчастей: оригинальные детали, поиск и оформление заказов.';
        break;
    case 'login':
        $title = 'Вход';
        $metaKeywords = 'авторизация, вход в аккаунт, личный кабинет';
        $metaDescription = 'Войдите в свой аккаунт Мото-Профи, чтобы управлять заказами и профилем.';
        break;
    case 'signup':
        $title = 'Регистрация';
        $metaKeywords = 'регистрация, создать аккаунт, мото магазин';
        $metaDescription = 'Создайте аккаунт в интернет-магазине Мото-Профи.';
        break;
    case 'verificator':
        $title = 'Подтверждение операции';
        $metaKeywords = 'подтверждение заказа, верификация почты, подтверждение платежа';
        $metaDescription = 'Страница подтверждения операций и заказов.';
        break;
    case 'profile':
        $title = 'Личный кабинет';
        $metaKeywords = 'профиль пользователя, история заказов, мои данные';
        $metaDescription = 'Управляйте личной информацией, заказами и настройками аккаунта.';
        break;
    case 'cart':
        $title = 'Корзина';
        $metaKeywords = 'корзина, оформление заказа, мотозапчасти, купить запчасти';
        $metaDescription = 'Просмотр и оформление заказов на мотозапчасти и аксессуары.';
        break;
    case 'favourite':
        $title = 'Избранное';
        $metaKeywords = 'избранные мотозапчасти, отложенные товары, список желаний';
        $metaDescription = 'Список сохранённых и отложенных к покупке мототоваров.';
        break;
    case 'contacts':
        $title = 'Всегда на связи с вами';
        $metaKeywords = 'контакты, адрес, телефон, как проехать';
        $metaDescription = 'Контактная информация Мото-Профи: телефон, адрес, схема проезда.';
        break;
    case 'admin_form':
        $title = '...';
        $disableTitleSuffix = true;
        break;
    case 'admin_dashboard':
        $title = 'Административная панель';
        $metaKeywords = '';
        $metaDescription = '';
        break;
    case '404':
        $title = 'Страница не найдена';
        $metaKeywords = 'ошибка 404, страница не найдена';
        $metaDescription = 'К сожалению, страница не найдена. Проверьте адрес или вернитесь на главную.';
        break;
    default:
        $title = 'Мото-Профи';
        $disableTitleSuffix = false;
        $metaKeywords = 'мото магазин, запчасти';
        $metaDescription = 'Качественные запчасти и аксессуары для мотоциклов в Ижевске.';
        break;
}

// Включение главного шаблона
include __DIR__ . '/../app/modules/layout.php';

?>