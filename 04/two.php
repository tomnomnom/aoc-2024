<?php


class grid {
    public $grid;
    public $x = 0;
    public $y = 0;

    function __construct(array $grid){
        $this->grid = $grid;
    }

    function get($x, $y){
        if (!isset($this->grid[$y])) return "";
        if (!isset($this->grid[$y][$x])) return "";

        return $this->grid[$y][$x];
    }

    function getRel($x, $y){
        return $this->get(
            $this->x + $x,
            $this->y + $y
        );
    }

    function relRun(...$coords){
        $out = [];
        foreach ($coords as $c){
            $out[] = $this->getRel($c[0], $c[1]);
        }
        return $out;
    }

    function current(){
        return $this->get($this->x, $this->y);
    }

    function next(){
        $this->x++;
        if ($this->x >= sizeOf($this->grid[$this->y])){
            $this->x = 0;
            $this->y++;
        }
    }

    function lookAround(){

        $a = $this->relRun([-1, -1], [1, 1]);
        $b = $this->relRun([1, -1], [-1, 1]);

        sort($a);
        sort($b);
        
        return ($a[0] == "M" && $a[1] == "S" &&
                $b[0] == "M" && $b[1] == "S");

    }   

}

$grid = new grid(array_map(function($line){
    return str_split(trim($line));
}, file("input")));

$found = 0;
while ($grid->current() != ""){
    if ($grid->current() == "A" && $grid->lookAround()){
        $found++;
    }
    $grid->next();
}

echo $found;

