<?php
require_once '../database/connect.php';

$stmt = $pdo->query("SELECT category_id, parent_id, category_name FROM categories ORDER BY category_name");
$all = $stmt->fetchAll(PDO::FETCH_ASSOC);

function buildTree($elements, $parentId = null) {
    $branch = [];
    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = buildTree($elements, $element['category_id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }
    return $branch;
}

echo json_encode(buildTree($all), JSON_UNESCAPED_UNICODE);
?>