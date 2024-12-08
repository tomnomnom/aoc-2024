<?php

class equation {
    public $val;
    public $nums = [];

    function __construct($in){
        list($val, $nums) = explode(": ", trim($in));

        $this->val = $val;
        $this->nums = explode(" ", $nums);
    }

    function search(){
        $ops = operators(sizeOf($this->nums)-1);
        foreach ($ops as $op){
            $r = $this->evaluate($op);
            if ($r == $this->val){
                return true;
            }
        }
        return false;
    }

    function evaluate($ops){
        $acc = $this->nums[0];
        for ($i = 1; $i < sizeOf($this->nums); $i++){
            $op = $ops[$i-1];
            $num = $this->nums[$i];

            switch($op){
            case "+":
                $acc += $num;
                break;
            case "*":
                $acc *= $num;
                break;
            }
        }  
        return $acc;
    }
}

function operators($count) {
    $combinations = 2**$count;
    for ($i = 0; $i < $combinations; $i++) {
        yield bitsToOps($i, $count);
    }
}

// I just *knew* when I wrote this that it would be a
// bad fit for part two, but I did it anyway ¯\_(ツ)_/¯
function bitsToOps($bits, $count){
    $out = [];
    for ($i = 0; $i < $count; $i++){
        if ($bits & (1 << $i)){
            $out[] = "+";
        } else {
            $out[] = "*";
        }
    }
    return $out;
}

$equations = array_map(
    fn($line) => new equation($line),
    file("input")
);

$acc = 0;
foreach ($equations as $eq){
    if ($eq->search()){
        $acc += $eq->val;
    }
}


echo $acc.PHP_EOL;

