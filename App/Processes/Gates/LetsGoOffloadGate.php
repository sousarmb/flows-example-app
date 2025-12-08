<?php

declare(strict_types=1);

namespace App\Processes\Gates;

use App\Processes\ParallelProcess_A;
use App\Processes\ParallelProcess_B;
use App\Processes\ParallelProcess_C;
use Flows\Gates\OffloadAndGate;

class LetsGoOffloadGate extends OffloadAndGate
{

    public function __invoke(): array
    {
        /*
         * PHP is single-threaded, the following processes will be 
         * run in separate PHP processes, all will receive the same 
         * input and the parent PHP process waits for both to 
         * finish, their output will be aggregated into a collection 
         * and may be used in the next tasks 
         */
        return [
            ParallelProcess_A::class,
            ParallelProcess_B::class,
            ParallelProcess_C::class
        ];
    }

    public function cleanUp(bool $forSerialization = false): void {}
}
