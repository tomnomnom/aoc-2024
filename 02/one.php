<?php

$diffs = array_map(function($l) {
    $in = explode(" ", trim($l));
    $prev = $in[0];
    $out = [];
    for ($i = 1; $i < sizeOf($in); $i++){
        $cur = $in[$i];
        $out[] = $prev - $cur;
        $prev = $cur;
    }

    return $out;
}, file("input"));

$safe = array_filter($diffs, function($diff){
    $neg = $diff[0] < 0;
    foreach ($diff as $d){
        if ($d == 0) return false;
        if (abs($d) > 3) return false; 
        if ($neg != ($d < 0)) return false;
    }

    return true;
});

echo sizeOf($safe).PHP_EOL;
