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
        $candidates = [
            // Up
            $this->relRun([0, -1], [0, -2], [0, -3]),
            // Down
            $this->relRun([0, 1], [0, 2], [0, 3]),
            // Left
            $this->relRun([-1, 0], [-2, 0], [-3, 0]),
            // Right
            $this->relRun([1, 0], [2, 0], [3, 0]),
            // UpLeft
            $this->relRun([-1, -1], [-2, -2], [-3, -3]),
            // UpRight
            $this->relRun([1, -1], [2, -2], [3, -3]),
            // DownLeft
            $this->relRun([-1, 1], [-2, 2], [-3, 3]),
            // DownRight
            $this->relRun([1, 1], [2, 2], [3, 3]),
        ]; 

        $count = 0;
        foreach ($candidates as $c){
            if (implode("", $c) == "MAS") $count++;
        }
        return $count;
    }

}

$grid = new grid(array_map(function($line){
    return str_split(trim($line));
}, file("input")));

$found = 0;
while ($grid->current() != ""){
    if ($grid->current() == "X"){
        $found += $grid->lookAround();
    }
    $grid->next();
}

echo $found;

