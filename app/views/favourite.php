<?php

require_once '../database/connect.php';

$inCart = [];

if (isset($_SESSION['user']['user_id'])) {
    $userId = $_SESSION['user']['user_id'];
    $stmt = $pdo->prepare("SELECT product_id FROM favourites WHERE user_id = ?");
    $stmt->execute([$userId]);
    $inCart = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<section class="container indent-mt">
    <div id="favouriteWrapper">

        <!-- Пустое состояние -->
        <div class="empty-cart-message flex fd-c ai-c jc-c<?= empty($inCart) ? '' : ' hidden' ?>" id="favouriteEmptyState">
            <p class="dg3-text ff-usb average-text">В избранном пока нет товаров</p>
        </div>

        <!-- Основной блок -->
        <div class="flex jc-sb ai-fs catalog-cg<?= empty($inCart) ? ' hidden' : '' ?>" id="favouriteMainBlock">
            <!-- Фильтры -->
            <div class="bg-lb catalog-filter sticky-side">
                <div class="flex fd-c catalog-filter-inner">

                    <!-- В наличии -->
                    <div class="catalog-filter-section">
                        <div class="flex jc-sb ai-c toggle-wrapper">
                            <p class="ff-um dg3-text small-text">В наличии</p>
                            <label class="toggle-switch">
                                <input class="input-switch" type="checkbox" id="fav_in_stock" name="in_stock">
                                <span class="switch"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Сортировка -->
                    <div class="catalog-filter-section flex fd-c">
                        <p class="ff-um dg3-text small-text">Сортировка</p>
                        <div class="sort-dropdown">
                            <button id="sortDropdownBtn" class="sort-button ff-ur small-text dg3-bborder">Недавно добавленные</button>
                            <ul id="sortDropdownList" class="sort-options ff-ur small-text dg3-bborder">
                                <li class="sort-option active" data-sort="recent">Недавно добавленные</li>
                                <li class="sort-option" data-sort="old">Давно добавленные</li>
                                <li class="sort-option" data-sort="cheap">Сначала дешёвые</li>
                                <li class="sort-option" data-sort="expensive">Сначала дорогие</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Список товаров -->
            <div class="bg-lb bindent-p w-100 catalog-grid" id="favouriteContainer"></div>
        </div>
    </div>
</section>

