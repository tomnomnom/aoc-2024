<?php

// It might just be me, but I found this super difficult without
// just brute-forcing it like this. I assume there's a "smart" way
// to solve or a trick I'm missing :/
$safe = array_filter(file("input"), function($l) {
    $report = explode(" ", trim($l));
    $safe = isSafe($report);

    if ($safe){
        return true;
    }

    foreach (variations($report) as $variant){
        if (isSafe($variant)){
            return true;
        }
    }

    return false;
});

function isSafe($report) {
    $neg = 0;
    $pos = 0;

    $prev = $report[0];

    for ($i = 1; $i < sizeOf($report); $i++){
        $cur = $report[$i];
        $diff = $prev - $cur;

        if ($diff == 0) return false;
        if (abs($diff) > 3) return false;
        if ($diff < 0) $neg++;
        if ($diff > 0) $pos++;

        $prev = $cur;
    }

    return $neg == 0 || $pos == 0;
}

function variations($report){
    $variations = array_fill(0, sizeOf($report), $report);
    $out = [];
    
    for ($i = 0; $i < sizeOf($variations); $i++){
        unset($variations[$i][$i]);
        $out[] = array_values($variations[$i]);
    }

    return $out;
}

echo sizeOf($safe).PHP_EOL;


