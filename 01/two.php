<?php

$lines = array_map(fn($l) => explode("   ", trim($l)), file("input"));

$a = [];
$b = [];

foreach ($lines as $line){
    $a[] = $line[0];
    if (!isset($b[$line[1]])){
        $b[$line[1]] = 0;
    }
    $b[$line[1]]++;
}


$scores = array_map(fn($id) => $id*($b[$id]?? 0), $a);

echo array_sum($scores);

