<?php

require_once __DIR__ . '/vendor/autoload.php';

use PixelRust\PerlinNoise;

$tileSize = 16;
$rows = 50;
$columns = 50;

$perlinNoise = new PerlinNoise(3000);

echo json_encode([
    'rows' => $rows,
    'columns' => $columns,
    'tileSize' => $tileSize,
    'map' => $perlinNoise->generate($rows, $columns)
]);
