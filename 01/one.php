<?php

$lines = array_map(fn($l) => explode("   ", trim($l)), file("input"));

$a = [];
$b = [];

foreach ($lines as $line){
    $a[] = $line[0];
    $b[] = $line[1];
}

sort($a);
sort($b);

$diffs = array_map(fn($x, $y) => abs($x - $y), $a, $b);

echo array_sum($diffs);

