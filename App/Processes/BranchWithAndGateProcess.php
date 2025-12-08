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
        $docs = <<<TEXT
This demo uses a process with 3 tasks.

The first creates the input for the next tasks, the second increments the input.

After the second task the process branches into 3 other processes using an AND 
gate. The three branch processes all receive the same input: the output from the 
second process.

fter the three branch processes are finished, they return their outputs to 
the "parent" process and the flow resumes in the parent process (processes branched 
with AND gates always return to the "parent process").

The third task receives the output from the three branches and adds it.\n\n
TEXT;
        echo __CLASS__ . ": $docs";
        readline("Press return key to continue ...\n");
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
