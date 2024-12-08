<?php


class grid {
    public $grid;
    public $x = 0;
    public $y = 0;

    public $turns = 0;
    public $d = [
        [0, -1], // up
        [1, 0], // right
        [0, 1], // down
        [-1, 0], // left
    ];

    public $visited = [];

    function __construct(array $grid){
        $this->grid = $grid;

        for ($y = 0; $y < sizeOf($grid); $y++){
            for ($x = 0; $x < sizeOf($grid[$y]); $x++){
                if ($grid[$y][$x] == "^"){
                    $this->x = $x;
                    $this->y = $y;
                }
            }
        }
    }

    function get($x, $y){
        if (!isset($this->grid[$y])) return "";
        if (!isset($this->grid[$y][$x])) return "";

        return $this->grid[$y][$x];
    }

    function turn(){
        $this->turns++;
    }

    function dx(){
        return $this->d[$this->turns % 4][0];
    }

    function dy(){
        return $this->d[$this->turns % 4][1];
    }

    function step(){
        $key = sprintf("%d x %d", $this->x, $this->y);
        $this->visited[$key] = true;
        $this->x += $this->dx();
        $this->y += $this->dy();
    }

    function next(){
        return $this->get(
            $this->x + $this->dx(),
            $this->y + $this->dy(),
        );
    }
}

$grid = new grid(array_map(function($line){
    return str_split(trim($line));
}, file("input")));

do {
    if ($grid->next() == "#"){
        $grid->turn();
    }
    $grid->step();

} while($grid->next() != "");

echo sizeOf($grid->visited)+1;

