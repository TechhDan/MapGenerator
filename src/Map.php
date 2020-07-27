<?php

namespace PixelRust;

class Map {

    public $rows = 100;
    public $columns = 100;
    public $tileSize = 16;

    public $deathLimit;
    public $birthLimit;
    public $numberOfSimulationSteps;
    public $chanceToStartAlive;

    public function __construct()
    {
        $this->deathLimit = mt_rand(0, 8);
        $this->birthLimit = mt_rand(0, 8);
        $this->numberOfSimulationSteps = mt_rand(1, 5);
        $this->chanceToStartAlive = mt_rand(0, 100);
    }

    public function generateMap(): array
    {
        $cellmap = $this->mapArrayGenerator($this->chanceToStartAlive, $this->rows, $this->columns);
        for ($i = 0; $i < $this->numberOfSimulationSteps; $i++) { 
            $cellmap = $this->doSimulationStep($cellmap);
        }
        return $cellmap;
    }

    /**
     *
     * If living cells are surrounded by less than $deathLimit
     * cells they die, and if dead cells are near at least $birthLimit
     * cells they become alive.
     */
    private function doSimulationStep(array $oldMap): array
    {
        $newMap = $this->mapArrayGenerator(0, $this->rows, $this->columns);
        for ($x = 0; $x < count($oldMap); $x++) { 
            for ($y = 0; $y < count($oldMap[0]); $y++) { 
                $aliveNeighbours = $this->countAliveNeighbours($oldMap, $x, $y);
                // The new value is based on our simulation rules
                // First, if a cell is alive but has too few neighbours, kill it.
                if ($oldMap[$x][$y]) {
                    if ($aliveNeighbours < $this->deathLimit) {
                        $newMap[$x][$y] = false;
                    } else {
                        $newMap[$x][$y] = true;
                    }
                } else {
                    if ($aliveNeighbours > $this->birthLimit) {
                        $newMap[$x][$y] = true;
                    } else {
                        $newMap[$x][$y] = false;
                    }
                }
            }
        }
        return $newMap;
    }

    private function mapArrayGenerator(int $chanceToStartAlive, int $rows, int $columns)
    {
        $map = [];
        for ($i = 0; $i < $columns; $i++) {
            $map[$i] = [];
            for ($j = 0; $j < $rows; $j++) {
                $map[$i][$j] = mt_rand(0, 99) <  $chanceToStartAlive ? true : false;
            }
        }
        return $map;
    }

    private function countAliveNeighbours(array $map, int $x, int $y): int
    {
        $count = 0;
        for ($i = -1; $i < 2; $i++) { 
            for ($j = -1; $j < 2; $j++) { 
                $neighbourX = $x + $i;
                $neighbourY = $y + $j;
                // If we're looking at the middle point
                if ($i === 0 && $j === 0) {
                    // Do nothing. We don't want to add ourselves in
                } elseif ( // In case the index we're looking at is off the edge of the map
                    $neighbourX < 0 ||
                    $neighbourY < 0 ||
                    $neighbourX >= count($map) ||
                    $neighbourY >= count($map[0])
                ) {
                    $count++;
                } elseif ($map[$neighbourX][$neighbourY]) {
                    $count++;
                }
            }
        }
        return $count;
    }
}
