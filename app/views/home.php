 <?php require("../app/modules/banner.php"); ?>

<?php
$query = "
SELECT c.category_id, c.category_name, c.category_image, COUNT(p.product_id) AS product_count
FROM categories c
LEFT JOIN products p ON c.category_id = p.category_id
WHERE c.parent_id IS NULL
GROUP BY c.category_id
ORDER BY product_count DESC
LIMIT 8;
";

$stmt = $pdo->query($query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ВЫВОД 8 ПОПУЛЯРНЫХ КАТЕГОРИЙ -->
<div class="container indent-mt">
    <p class="section-title ff-usb dg3-text hindent-mb">Популярные категории</p>
    <section class="popular-categories">
        <div class="categories-grid">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <a href="category.php?id=<?= $category['category_id']; ?>" class="category-card">
                        <div class="category-image" style="background-image: url('../public/categories/<?= $category['category_image']; ?>');"></div>
                        <p class="category-name ff-usb" data-length="<?= strlen($category['category_name']); ?>">
                            <?= $category['category_name']; ?> (<?= $category['product_count']; ?>)
                        </p>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <section class="container indent-mt">
                    <p class="section-title ff-usb dg3-text hindent-mb">Нет популярных категорий</p>
                </section>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- СЕКЦИЯ С ЧАВО АККОРДЕОНАМИ -->
<section class="container indent-mt">
    <div class="flex jc-sb faq-cg">
        <img src="../public/assets/faq.png" class="home-image" alt="Часто задаваемые вопросы">
        <div class="flex fd-c">
            <p class="section-title ff-usb dg3-text hindent-mb">Часто задаваемые вопросы</p>

            <!-- ЧАВО АККОРДЕОНЫ -->
            <div class="accordion-container">
                <div class="accordion accordion-main">
                    <button class="accordion-header flex jc-sb ai-c ff-um dg3-text subsection-title">
                        Как оформить заказ?
                        <div class="accordion-icon">
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                    <div class="accordion-content">
                        <p class="ff-ur dg3-text">
                        Для оформления заказа выберите нужные товары, добавьте их в корзину и перейдите к оформлению. Заполните контактные данные и выберите способ доставки. После этого подтвердите заказ, и мы свяжемся с вами для уточнения деталей.
                        </p>
                    </div>
                </div>

                <div class="accordion accordion-main">
                    <button class="accordion-header flex jc-sb ai-c ff-um dg3-text subsection-title">
                        Как проходит доставка?
                        <div class="accordion-icon">
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                    <div class="accordion-content">
                        <p class="ff-ur dg3-text">
                        Мы осуществляем доставку по Ижевску и ближайшим регионам. Заказы обрабатываются в течение одного рабочего дня. Доставка осуществляется курьером или почтой, в зависимости от вашего выбора при оформлении заказа.
                        </p>
                    </div>
                </div>

                <div class="accordion accordion-main">
                    <button class="accordion-header flex jc-sb ai-c ff-um dg3-text subsection-title">
                        Можно ли вернуть товар?
                        <div class="accordion-icon">
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                    <div class="accordion-content">
                        <p class="ff-ur dg3-text">
                        Да, вы можете вернуть товар в течение 14 дней с момента получения. Для этого свяжитесь с нашей службой поддержки, и мы предоставим инструкции по возврату. Товар должен быть в оригинальной упаковке и не использоваться.
                        </p>
                    </div>
                </div>
            </div>
            <!-- ЧАВО АККОРДЕОНЫ -->
        </div>
    </div>
</section>