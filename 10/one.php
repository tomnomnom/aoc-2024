<?php

class map {
    public $grid;

    function __construct($grid){
        $this->grid = $grid;
    }

    function get($c){
        list($x, $y) = $c;
        if (!isset($this->grid[$y])) return null;
        if (!isset($this->grid[$y][$x])) return null;
        
        return $this->grid[$y][$x];
    }

    function findTrailheads(){
        $out = [];
        for ($y = 0; $y < sizeOf($this->grid); $y++){
            $row = $this->grid[$y];
            for ($x = 0; $x < sizeOf($row); $x++){
                $height = $row[$x];
                if ($height != 0) continue;
                $out[] = sizeOf($this->findNines([$x, $y], 0));
            }
        }
        return $out;
    }

    function findNines($c, $prev){
        if ($prev == 9) return [$c];

        list($x, $y) = $c;
        
        $steps = [
            [$x-1, $y],
            [$x, $y-1],
            [$x+1, $y],
            [$x, $y+1],
        ];

        $nines = [];
        foreach ($steps as $step){
            if ($this->get($step) != ($prev + 1)){
                continue;
            }

            array_push($nines, ...$this->findNines($step, $prev + 1));
        }

        return array_unique($nines, SORT_REGULAR);
    }
}   

$map = new map(array_map(
    fn($line) => str_split(trim($line)),
    file("input"),
));

$scores = $map->findTrailheads();

echo array_sum($scores).PHP_EOL;

