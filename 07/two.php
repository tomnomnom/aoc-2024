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
            case "||":
                $acc .= $num;
                break;
            }
        }  
        return $acc;
    }
}

function operators($count) {
    $combinations = 3**$count;
    for ($i = 0; $i < $combinations; $i++) {
        yield intToOps($i, $count);
    }
}

// when all you have is a hammer...
function intToOps($int, $count){
    $out = [];

    // lol
    $base3 = base_convert($int, 10, 3);
    $field = str_split(str_pad($base3, $count, "0", STR_PAD_LEFT));

    for ($i = 0; $i < $count; $i++){
        switch ($field[$i]){
        case "0":
            $out[] = "+";
            break;
        case "1":
            $out[] = "*";
            break;
        case "2":
            $out[] = "||";
            break;
        }
    }
    return $out;
}

$equations = array_map(
    fn($line) => new equation($line),
    file("example")
);

$acc = 0;
foreach ($equations as $eq){
    if ($eq->search()){
        $acc += $eq->val;
    }
}


echo $acc.PHP_EOL;

