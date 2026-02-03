<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Gates\AndGate;
use Flows\Processes\Process;

class BranchWithAndGateProcess extends Process
{
    public function __construct()
    {
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    return new readonly class(1) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    return new readonly class($io->get('counter') + 1) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class extends AndGate {
                public function __invoke(): array
                {
                    /*
                     * PHP is single-threaded, the following processes will be run 
                     * in a pseudo parallel manner, meaning all will receive the 
                     * same input and their output will be aggregated into a collection
                     * and may be used in the next tasks, but their execution will 
                     * be in the order as in the array, inside this PHP process
                     */
                    return [
                        ParallelProcess_A::class,
                        ParallelProcess_B::class,
                        ParallelProcess_C::class
                    ];
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $total = $io->get(ParallelProcess_A::class)->get('counter')
                        + $io->get(ParallelProcess_B::class)->get('counter')
                        + $io->get(ParallelProcess_C::class)->get('counter');
                    return new readonly class($total) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
        ];

        parent::__construct();
    }
}
