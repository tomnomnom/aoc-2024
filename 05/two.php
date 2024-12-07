<?php


class rule {
    public $before;
    public $after;

    public function __construct($val){
        $parts = explode("|", trim($val));
        $this->before = $parts[0];
        $this->after = $parts[1];
    }

    public function applies(array $update){
        if (!isset($update[$this->before])) return false;
        if (!isset($update[$this->after])) return false;
        return true;
    }

    public function met(array $update){
        if (!$this->applies($update)) return true;

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

    public function fix(array $update){
        // Get the updates that apply
        $rules = [];
        foreach ($this->rules as $rule){
            if ($rule->applies($update)){
                $rules[] = $rule;
            }
        }

        foreach ($update as $page => $i){
            $score = 0;
            foreach ($rules as $rule){
                if ($page == $rule->before) $score--;
                if ($page == $rule->after) $score++;
            }
            $update[$page] = $score;
        }
        asort($update);
        return $update;
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

$invalid = [];
foreach ($updates as $update){
    if (!$rules->met($update)){
        $invalid[] = $update;
    }
}

foreach ($invalid as $k => $update){
    $invalid[$k] = $rules->fix($update);
}

echo middleSum($invalid);

