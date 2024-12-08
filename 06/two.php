<?php
// This solution is... Not elegant and not fast (:

class grid {
    public $grid;
    public $x = 0;
    public $y = 0;

    public $startX = 0;
    public $startY = 0;

    public $extraX = 0;
    public $extraY = 0;

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
                    $this->startX = $x;

                    $this->y = $y;
                    $this->startY = $y;
                }
            }
        }

    }

    function nextObstacle(){
        $this->x = $this->startX;
        $this->y = $this->startY;
        $this->visited = [];
        $this->turns = 0;

        $this->extraX++;
        if ($this->extraX >= sizeOf($this->grid[$this->extraY])){
            $this->extraX = 0;
            $this->extraY++;
        }
        if ($this->extraY >= sizeOf($this->grid)){
            // non left
            return false;
        }

        return true;
    }

    function get($x, $y){
        if (!isset($this->grid[$y])) return "";
        if (!isset($this->grid[$y][$x])) return "";

        if ($x == $this->extraX && $y == $this->extraY) return "#";

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
        $key = sprintf("%d x %d -> %d x %d", $this->x, $this->y, $this->dx(), $this->dy());

        // Hit a loop
        if (isset($this->visited[$key])){
            return true;
        }
        $this->visited[$key] = true;

        $this->x += $this->dx();
        $this->y += $this->dy();

        return false;
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

$loops = 0;
while(true) {
    if ($grid->next() == "#"){
        $grid->turn();
        // I missed this continue in part one
        // and got lucky that it didn't matter!
        continue;
    }

    $loop = $grid->step();
    if ($loop){
        $loops++;
    }

    if ($loop || $grid->next() == ""){
        if (!$grid->nextObstacle()){
            break;
        }
    }
}


echo $loops.PHP_EOL;

