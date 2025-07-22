<?php
require_once '../database/connect.php';

function getCategoriesTree($pdo, $parent_id = null) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE parent_id " . 
        (is_null($parent_id) ? "IS NULL" : "= :parent_id") . " ORDER BY category_name ASC");

    if (!is_null($parent_id)) {
        $stmt->execute(['parent_id' => $parent_id]);
    } else {
        $stmt->execute();
    }

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($categories as &$cat) {
        $cat['children'] = getCategoriesTree($pdo, $cat['category_id']);
    }

    return $categories;
}

$categoriesTree = getCategoriesTree($pdo);
?>

<div id="categorySidebarOverlay" class="category-sidebar-overlay"></div>
<div id="categorySidebar" class="category-sidebar">
    <div class="category-sidebar-header flex ai-c jc-sb">
        <p class="ff-um dg3-text popup-title">Каталог</p>
        <button class="popup-x flex ai-c jc-c" id="closeSidebarBtn" tabindex="0">
            <span></span>
            <span></span>
        </button>
    </div>
    <div class="category-sidebar-body">


        <div class="category-sidebar-left" id="mainCategoriesDesktop">
            <?php foreach ($categoriesTree as $category): ?>
				<a href="javascript:void(0);" 
				class="category-item ff-ur dg3-text body-text flex ai-c jc-sb"
				data-id="<?= $category['category_id'] ?>"
				data-name="<?= htmlspecialchars($category['category_name']) ?>"
				onclick="toggleCategory('<?= $category['category_id'] ?>', '<?= htmlspecialchars($category['category_name']) ?>')">
					<?= htmlspecialchars($category['category_name']) ?>
					<?php if (!empty($category['children'])): ?>
						<img src="../public/assets/sarrow-down.svg" alt="→" class="mobile-sidebar-arrow">
					<?php endif; ?>
				</a>
            <?php endforeach; ?>
        </div>


        <div class="category-sidebar-right" id="subCategories">
            <?php foreach ($categoriesTree as $category): ?>
                <div class="subcategories-container" data-parent="<?= $category['category_id'] ?>">
                    <div class="subcategories-grid">
                        <?php
                        $chunks = array_chunk($category['children'], ceil(count($category['children']) / 2));
                        foreach ($chunks as $chunk): ?>
                            <div class="subcategory-column">
                                <?php foreach ($chunk as $sub): ?>
                                    <div class="subcategory-block">
                                        <?php if (!empty($sub['children'])): ?>
                                            <div class="sidebar-accordion">
                                                <div class="sidebar-accordion-header flex ai-c jc-sb">
                                                    <a href="index.php?page=catalog&category_id=<?= $sub['category_id'] ?>" 
                                                       class="subcategory-link ff-um dg3-text">
                                                        <?= htmlspecialchars($sub['category_name']) ?>
                                                    </a>
                                                    <button class="sidebar-accordion-arrow-btn" type="button">
                                                        <img src="../public/assets/sarrow-down.svg" alt="▼" class="sidebar-accordion-arrow">
                                                    </button>
                                                </div>
                                                <div class="sidebar-accordion-content">
                                                    <ul class="sub-sub-list">
                                                        <?php foreach ($sub['children'] as $subsub): ?>
                                                            <li>
                                                                <a href="index.php?page=catalog&category_id=<?= $subsub['category_id'] ?>" class="ff-ur g2-text small-text">
                                                                    <?= htmlspecialchars($subsub['category_name']) ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <a href="index.php?page=catalog&category_id=<?= $sub['category_id'] ?>" 
                                               class="subcategory-simple-link body-text ff-um dg3-text">
                                                <?= htmlspecialchars($sub['category_name']) ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>
<script>
function toggleCategory(categoryId, categoryName) {
    const event = new CustomEvent('categorySelected', {
        detail: { categoryId, categoryName }
    });
    document.dispatchEvent(event);
}
</script>