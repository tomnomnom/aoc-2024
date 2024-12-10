<?php


function mapToBlocks($map){
    $space = false;

    $out = [];
    for ($i = 0; $i < sizeOf($map); $i++){
        $size = $map[$i];
        $val = $i/2;
        if ($space) {
            $val = ".";
        }

        $blocks = array_fill(0, $size, $val);
        array_push($out, ...$blocks);

        $space = !$space;
    }

    return $out;
}

function checksum($blocks){
    $acc = 0;
    for ($i = 0; $i < sizeOf($blocks); $i++){
        if ($blocks[$i] == ".") continue;
        $acc += $i * $blocks[$i];
    }
    return $acc;
}

class blocks {
    public $blocks = [];
    public $files = [];

    public function __construct($blocks){
        $this->blocks = $blocks;

        for ($i = 0; $i < sizeOf($blocks); $i++){
            $block = $blocks[$i];
            if ($block == "."){
                continue;
            }

            if (!isset($this->files[$block])){
                $this->files[$block] = [];
            }
            $this->files[$block][] = $i;
        }
    }

    function findFreeRegion($requestedSize, $before){
        $inFree = false;
        $start = 0;

        for ($i = 0; $i <= $before; $i++){
            $block = $this->blocks[$i];
            if ($inFree && $block == "."){
                continue;
            }
            if (!$inFree && $block == "."){
                $start = $i;
                $inFree = true;
                continue;
            }
            if ($inFree && $block != "."){
                $regionSize = $i - $start;
                if ($regionSize < $requestedSize){
                    $inFree = false;
                    continue;
                }
                return $start;
            }
        }

        return null;
    }

    function moveFile($id){
        $blocks = $this->files[$id];
        $regionStart = $this->findFreeRegion(sizeOf($blocks), $blocks[0]);
        if ($regionStart === null) return;

        $shift = $blocks[0] - $regionStart;
        foreach ($blocks as &$block){
            $this->blocks[$block] = ".";
            $this->blocks[$block - $shift] = $id;
            $block -= $shift;
        }
    }

    function defrag(){
        $ids = array_reverse(array_keys($this->files));
        foreach ($ids as $id){
            $this->moveFile($id);
        }
    }
}

$in = str_split(trim(file_get_contents("input")));
$blocks = mapToBlocks($in);

$blocks = new blocks($blocks);
$blocks->defrag();

echo checksum($blocks->blocks).PHP_EOL;
