<?php
require_once '../database/connect.php';

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

if (!$categoryId) {
    die('ID категории не указан.');
}

try {
    $query = "WITH RECURSIVE category_tree AS (
                SELECT category_id, category_name, parent_id FROM categories WHERE category_id = :category_id_root
                UNION ALL
                SELECT c.category_id, c.category_name, c.parent_id FROM categories c
                JOIN category_tree ct ON c.parent_id = ct.category_id
              )
              SELECT * FROM category_tree WHERE category_id != :category_id_child ORDER BY category_name ASC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':category_id_root', $categoryId, PDO::PARAM_INT);
    $stmt->bindParam(':category_id_child', $categoryId, PDO::PARAM_INT);
    $stmt->execute();
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($subcategories) {
        $limit = 6;
        foreach ($subcategories as $i => $subcategory) {
            $subcategoryId = $subcategory['category_id'];
            $subcategoryName = $subcategory['category_name'];
            $hiddenClass = ($i >= $limit) ? " hidden" : "";
            $level = ($subcategory['parent_id'] == $categoryId) ? 1 : 2; // Уровень вложенности

            echo "<div class='flex ai-fs subcategory catalog-checkbox-wrapper level-$level$hiddenClass'>
                    <input type='checkbox' id='subcategory_$subcategoryId' class='catalog-checkbox' checked>
                    <label for='subcategory_$subcategoryId' class='ff-ur dg3-text small-text'>
                        " . htmlspecialchars($subcategoryName) . "
                    </label>
                  </div>";
        }

        if (count($subcategories) > $limit) {
            echo '<div class="flex ai-fs" id="toggleCategoriesWrap">
                  <button class="ff-usb ab-text small-text underline" id="toggleCategoriesBtn">Показать все</button>
                </div>';
        }
    }
} catch (PDOException $e) {
    die("Ошибка загрузки подкатегорий: " . $e->getMessage());
}
?>
