<?php
$file = "d:\\Proyectos\\FrontICE\\LARAVELBTI\\resources\\views\\personal\\index.blade.php";
if (file_exists($file)) {
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    echo "Total lines: " . count($lines) . "\n";
    foreach ($lines as $num => $line) {
        if (stripos($line, 'busqueda') !== false || stripos($line, 'grupo') !== false) {
            echo "Line " . ($num + 1) . ": " . $line . "\n";
        }
    }
} else {
    echo "File not found\n";
}
