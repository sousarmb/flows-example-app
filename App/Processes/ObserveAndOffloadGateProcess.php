<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Gates\LetsGoOffloadGate;
use App\Processes\IO\CounterIO;
use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class ObserveAndOffloadGateProcess extends Process
{
    public function __construct()
    {
        $docs = <<<TEXT
This demo uses a process with 3 tasks. 

The first creates the input for the next tasks, the second increments the input.

After the second task the process branches into 3 other processes using an AND 
offload gate. They will run in separate processes (different #PID). 

The three branch processes all receive the same input: the output from the second 
process. After the three branch processes are finished, they return their outputs 
to the parent process and the flow resumes in the parent process.

Processes branched with AND gates always return to the parent process.

The third task receives the output from the three branches, adds it and recovers 
the #PID.\n\n
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
                    return new CounterIO($io->get('counter') + 1);
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            LetsGoOffloadGate::class,
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $pid = ['parent#PID' => getmypid()];
                    $pid[ParallelProcess_A::class . '#PID'] = $io->get(ParallelProcess_A::class)->get('pid');
                    $pid[ParallelProcess_B::class . '#PID'] = $io->get(ParallelProcess_B::class)->get('pid');
                    $pid[ParallelProcess_C::class . '#PID'] = $io->get(ParallelProcess_C::class)->get('pid');
                    $total = $io->get(ParallelProcess_A::class)->get('counter')
                        + $io->get(ParallelProcess_B::class)->get('counter')
                        + $io->get(ParallelProcess_C::class)->get('counter');
                    return new readonly class($total, $pid) extends IO {
                        public function __construct(
                            protected int $counter,
                            protected array $pid
                        ) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
        ];

        parent::__construct();
    }
}
