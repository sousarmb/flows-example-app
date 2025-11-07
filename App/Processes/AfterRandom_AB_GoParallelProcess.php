<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Gates\LetsGoOffloadGate;
use App\Processes\Gates\LetsGoParallerGate;
use App\Processes\ParallelProcessA;
use App\Processes\ParallelProcessB;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use Flows\Processes\Process;
use RuntimeException;

class AfterRandom_AB_GoParallelProcess extends Process
{

    public function __construct()
    {
        $this->tasks = [
            LetsGoParallerGate::class,
            new class implements Task {
                public function __invoke(?IO $io = null): ?IO
                {
                    // Check the output of the parallel processes
                    if (!$io->has(ParallelProcessA::class)) {
                        throw new RuntimeException('Missing ' . ParallelProcessA::class . ' IO');
                    }
                    if (!$io->has(ParallelProcessB::class)) {
                        throw new RuntimeException('Missing ' . ParallelProcessB::class . ' IO');
                    }
                    // All is well, continue to next task
                    return $io;
                }
                public function cleanUp(): void {}
            },
            LetsGoOffloadGate::class,
            new class implements Task {
                public function __invoke(?IO $io = null): ?IO
                {
                    return $io;
                }

                public function cleanUp(): void {}
            }
        ];
        parent::__construct();
    }
}
