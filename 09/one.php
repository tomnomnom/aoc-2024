<?php


function mapToBlocks($map){
    $space = false;

    $out = [];
    for ($i = 0; $i < sizeOf($map); $i++){
        $size = $map[$i];
        $val = $i/2;
        if ($space) {
            $val = ".";
        }

        $blocks = array_fill(0, $size, $val);
        array_push($out, ...$blocks);

        $space = !$space;
    }

    return $out;
}

function checksum($blocks){
    $acc = 0;
    for ($i = 0; $i < sizeOf($blocks); $i++){
        $acc += $i * $blocks[$i];
    }
    return $acc;
}

$in = str_split(trim(file_get_contents("input")));
$blocks = mapToBlocks($in);

foreach ($blocks as $i => $block){
    if ($block != ".") continue;
    do {
        $toMove = array_pop($blocks);
    } while($toMove == ".");
    $blocks[$i] = $toMove;
}
// have to re-index the array
$blocks = array_values($blocks);


echo checksum($blocks).PHP_EOL;
