<?php

class cell {
    public $x;
    public $y;

    public $f;

    public $isAntiNode = false;

    function __construct($x, $y){
        $this->x = $x;
        $this->y = $y;
    }

    function distance(cell $c){
        return [
            $this->x - $c->x,
            $this->y - $c->y,
        ];
    }

    function antiNodes(cell $c){
        list($x, $y) = $this->distance($c);
        if ($x == 0 && $y == 0) return [];

        return [
            [$this->x + $x, $this->y + $y],
            [$this->x - $x*2, $this->y - $y*2],
        ];
    }
}

class grid {
    public $grid = [];
    public $map = [];

    function __construct(array $raw){
        for ($y = 0; $y < sizeOf($raw); $y++){
            $this->grid[$y] = [];

            $row = $raw[$y];

            for ($x = 0; $x < sizeOf($raw); $x++){
                $cell = new cell($x, $y);
                $this->grid[$y][$x] = $cell;

                if ($row[$x] == "."){
                    continue;
                }

                $cell->f = $row[$x];

                if (!isset($this->map[$cell->f])){
                    $this->map[$cell->f] = [];
                }
                $this->map[$cell->f][] = $cell;
            }
        }
    } 

    function cellExists($c){
        list($x, $y) = $c;
        return isset($this->grid[$y]) && isset($this->grid[$y][$x]);
    }

    function getCell($c){
        if (!$this->cellExists($c)) return null;

        list($x, $y) = $c;
        return $this->grid[$y][$x];
    }

    function setAntiNode($c){
        if (!$this->cellExists($c)) return null;

        list($x, $y) = $c;
        $this->grid[$y][$x]->isAntiNode = true;
    }

    function setAntiNodes(){
        foreach ($this->map as $freq => $cells){
            foreach ($cells as $a){
                foreach ($cells as $b){
                    $antiNodes = $a->antiNodes($b);
                    foreach ($antiNodes as $node){
                        $this->setAntiNode($node);
                    }
                }
            }
        }
    }

    function countAntiNodes(){
        $count = 0;
        foreach ($this->grid as $row){
            foreach ($row as $cell) {
                if ($cell->isAntiNode) $count++;
            }
        }
        return $count;
    }


}

$raw = array_map(
    fn($line) => str_split(trim($line)),
    file("input"),
);

$grid = new grid($raw);
$grid->setAntiNodes();

echo $grid->countAntiNodes().PHP_EOL;

