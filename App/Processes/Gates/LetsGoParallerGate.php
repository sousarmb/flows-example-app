<?php

declare(strict_types=1);

namespace App\Processes\Gates;

use App\Processes\ParallelProcessA;
use App\Processes\ParallelProcessB;
use Flows\Gates\OrGate;

class LetsGoParallerGate extends OrGate
{

    public function __invoke(): array
    {
        /*
         * PHP is single-threaded, the following processes will be run in a 
         * pseudo parallel manner, meaning both will receive the same input 
         * and their output will be aggregated into a collection and may be
         * used in other tasks, but their execution will be in the order as 
         * in the array, inside this PHP process
         */
        return [
            ParallelProcessA::class,
            ParallelProcessB::class
        ];
    }
}
