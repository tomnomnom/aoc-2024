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

    // Is the the best way to do this? Absolutely not (:
    function antiNodes(cell $c, $xLimit, $yLimit){
        list($x, $y) = $this->distance($c);
        if ($x == 0 && $y == 0) return [];

        $gcd = gmp_intval(gmp_gcd($x, $y));
        $stepX = $x/$gcd;
        $stepY = $y/$gcd;

        $antiNodes = [];

        $x = $this->x;
        $y = $this->y;
        while(true) {
            $x = $x + $stepX;
            $y = $y + $stepY;

            if ($x < 0 || $x > $xLimit) break;
            if ($y < 0 || $y > $yLimit) break;

            $antiNodes[] = [$x, $y];
        }

        $x = $this->x;
        $y = $this->y;
        while(true) {
            $x = $x - $stepX;
            $y = $y - $stepY;

            if ($x < 0 || $x > $xLimit) break;
            if ($y < 0 || $y > $yLimit) break;

            $antiNodes[] = [$x, $y];
        }

        return $antiNodes;
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
        $yLimit = sizeOf($this->grid) - 1;
        $xLimit = sizeOf($this->grid[0]) - 1;
        foreach ($this->map as $freq => $cells){
            foreach ($cells as $a){
                foreach ($cells as $b){
                    $antiNodes = $a->antiNodes($b, $xLimit, $yLimit);
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

