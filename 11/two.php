<?php


$GLOBALS['cache'] = [];

function blinkSize($num, $count){

    if ($count == 0){
        return 1;
    }

    $key = "$num-$count";
    if (isset($GLOBALS['cache'][$key])){
        return $GLOBALS['cache'][$key];
    }

    $count--;

    $bs = 0;
    $len = floor(log10($num)+1);

    if ($num == 0){
        $bs = blinkSize(1, $count);
    } else if (($len % 2) == 0){
        $parts = str_split($num, $len/2);
        $bs = blinkSize((int) $parts[0], $count) +
              blinkSize((int) $parts[1], $count);
    } else {
        $bs = blinkSize($num * 2024, $count);
    }

    $GLOBALS['cache'][$key] = $bs;

    return $bs;
}

$stones = array_map(
    fn($num) => (int) $num,
    explode(" ", trim(file_get_contents("input")))
);

$acc = 0;
foreach ($stones as $stone){
    $acc += blinkSize($stone, 75);
}

echo $acc.PHP_EOL;
