<?php


class rule {
    public $before;
    public $after;

    public function __construct($val){
        $parts = explode("|", trim($val));
        $this->before = $parts[0];
        $this->after = $parts[1];
    }

    public function met(array $update){
        if (!isset($update[$this->before])) return true;
        if (!isset($update[$this->after])) return true;

        $bi = $update[$this->before];
        $ai = $update[$this->after];
        return $bi < $ai;
    }
}

class ruleset {
    public $rules;

    public function add($rule){
        $this->rules[] = new rule($rule);
    }

    public function met(array $update){
        foreach ($this->rules as $rule){
            if (!$rule->met($update)){
                return false;
            }
        }
        return true;
    }
}

function parseUpdate($in){
    $out = [];

    $parts = explode(",", trim($in));

    $i = 0;
    foreach ($parts as $p){
        $out[$p] = $i;
        $i++;
    }
    return $out;
}

function middleSum($updates){
    $sum = 0;


    foreach($updates as $update){
        $vals = array_keys($update);
        $i = floor(sizeOf($update) / 2);
        $sum += $vals[$i];
    }
    return $sum;
}

$lines = file("input");
$rules = new ruleset();
$updates = [];

$inUpdates = false;
foreach ($lines as $line){
    if (trim($line) == ""){
        $inUpdates = true;
        continue;
    }

    if ($inUpdates){
        $updates[] = parseUpdate($line);
        continue;
    }

    $rules->add($line);
}

$valid = [];
foreach ($updates as $update){
    if ($rules->met($update)){
        $valid[] = $update;
    }
}

echo middleSum($valid);
