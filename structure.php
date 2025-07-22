<?php
function printStructure($dir, $prefix = '', &$output = []) {
    $items = array_diff(scandir($dir), ['.', '..']);
    sort($items); // Сортировка для читаемости
    $count = count($items);
    $i = 0;

    foreach ($items as $item) {
        $i++;
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $isLast = ($i === $count);
        $connector = $isLast ? '└── ' : '├── ';
        $line = $prefix . $connector . $item;
        $output[] = $line;

        if (is_dir($path)) {
            $newPrefix = $prefix . ($isLast ? '    ' : '│   ');
            printStructure($path, $newPrefix, $output);
        }
    }
}

$root = __DIR__;
$projectName = basename($root);

$output = ["{$projectName}/"];
printStructure($root, '', $output);

file_put_contents('structure.txt', implode(PHP_EOL, $output));
echo "✅ Структура проекта сохранена в structure.txt\n";
?>
