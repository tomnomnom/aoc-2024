<?php

class map {
    public array $grid = [];

    public function __construct(array $rows){
        foreach ($rows as $y => $row){
            foreach ($row as $x => $v){
                if (!isset($this->grid[$y])){
                    $this->grid[$y] = [];
                }

                $p = new plot([$x, $y], $v);
                $this->grid[$y][$x] = $p;
            }
        }


        foreach ($this->grid as $row){
            foreach ($row as $p){
                foreach ($this->neighbours($p) as $n){
                    $p->region->join($n->region);
                }
            } 
        }
    }

    public function neighbours(plot $p){
        $neighbours = [
            [-1, 0], // left
            [0, -1], // above
            [1, 0],  // right
            [0, 1],  // below
        ];

        $out = [];
        foreach ($neighbours as $n){
            
            $n = $this->getPlotRel($p, $n);
            if ($n === null) continue;
            if ($n->v != $p->v) continue;

            $out[] = $n;
        }

        return $out;
    }

    public function getPlotRel(plot $p, $d){

        list($x, $y) = $p->c;
        list($dx, $dy) = $d;
        
        $x += $dx;
        $y += $dy;

        return $this->getPlot([$x, $y]);
    }


    public function getPlot($c){
        list($x, $y) = $c;
        
        if (!isset($this->grid[$y])) return null;
        if (!isset($this->grid[$y][$x])) return null;

        return $this->grid[$y][$x];
    }

    public function uniqueRegions(){
        $out = [];
        foreach ($this->grid as $row){
            foreach ($row as $p){
                $out[spl_object_id($p->region)] = $p->region;
            }
        }
        return array_values($out);
    }   

    public function perimeter(region $r){
        $perimeter = 0;

        $neighbours = [
            [-1, 0], // left
            [0, -1], // above
            [1, 0],  // right
            [0, 1]   // below
        ];

        foreach ($r->plots as $p){
            foreach ($neighbours as $n){
                $n = $this->getPlotRel($p, $n);

                if ($n === null || $n->v != $p->v) $perimeter++;
            }
        }

        return $perimeter;
    }

    function price(){
        $price = 0;
        foreach ($this->uniqueRegions() as $region){
            printf(
                "%s: %d * %d = %d\n",
                $region->v,
                $region->area(),
                $this->perimeter($region),
                $region->area() * $this->perimeter($region)
            );
            $price += $region->area() * $this->perimeter($region);
        }
        return $price;
    }

}

class region {
    public $v;
    public array $plots = [];

    function addPlot(plot $p){
        if ($this->v == ""){
            $this->v = $p->v;
        }

        if ($p->v != $this->v) return;

        $p->region = $this;
        $this->plots[$p->key()] = $p;
    }

    function join(region $r){
        foreach ($r->plots as $k => $p){
            $p->region = $this;
            $this->plots[$k] = $p;
        }
        return $this;
    }

    function area(){
        return sizeOf($this->plots);
    }
}

class plot {
    public $c;
    public $v;
    public region $region;

    public function __construct($c, $v){
        $this->c = $c;
        $this->v = $v;
        $this->region = new region();
        $this->region->addPlot($this);
    }

    function key(){
        list($x, $y) = $this->c;
        return "$x,$y";
    }
}

$grid = new map(
    array_map(
        fn($row) => str_split(trim($row)),
        file("input")
    )
);


echo $grid->price().PHP_EOL;
