<?php

function blink($in){
    $out = [];

    foreach ($in as $stone){
        if ($stone == 0){
            $out[] = 1;
            continue;
        }

        $len = strlen($stone);
        if (($len % 2) == 0){
            $parts = str_split($stone, $len/2);
            $out[] = (int) $parts[0];
            $out[] = (int) $parts[1];
            continue;
        }

        $out[] = $stone * 2024;
    }

    return $out;
}

$stones = array_map(
    fn($num) => (int) $num,
    explode(" ", trim(file_get_contents("input")))
);

for ($i = 0; $i < 25; $i++){
    $stones = blink($stones);
}

echo sizeOf($stones).PHP_EOL;

