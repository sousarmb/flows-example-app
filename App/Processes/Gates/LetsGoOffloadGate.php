<?php

declare(strict_types=1);

namespace App\Processes\Gates;

use App\Processes\ParallelProcessA;
use App\Processes\ParallelProcessB;
use Flows\Gates\OffloadOrGate;

class LetsGoOffloadGate extends OffloadOrGate
{

    public function __invoke(): array
    {
        /*
         * PHP is single-threaded, the following processes will be run in 
         * separate PHP processes, both will receive the same input 
         * and the parent PHP process waits for both to finish, their output 
         * will be aggregated into a collection and may be used in other tasks 
         */
        return [
            ParallelProcessA::class,
            ParallelProcessB::class
        ];
    }
}
