<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Tasks\CounterAndPidOffloadedTask;
use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\ApplicationKernel;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class ParallelProcess_B extends Process
{
    public function __construct()
    {
        /* This process is used in 2 demos, check which one is running:
         * - demo-branch-and-gate-offload
         * - demo-branch-and-gate */
        if (ApplicationKernel::isOffloadedProcess()) {
            $this->tasks = [
                CounterAndPidOffloadedTask::class
            ];
        } else {
            $this->tasks = [
                new class implements TaskContract {
                    public function __invoke(?IOContract $io = null): ?IOContract
                    {
                        return new readonly class($io->get('counter') + 1) extends IO {
                            public function __construct(protected int $counter) {}
                        };
                    }

                    public function cleanUp(bool $forSerialization = false): void {}
                },
                new class implements TaskContract {
                    public function __invoke(?IOContract $io = null): ?IOContract
                    {
                        return new readonly class($io->get('counter') + 2) extends IO {
                            public function __construct(protected int $counter) {}
                        };
                    }

                    public function cleanUp(bool $forSerialization = false): void {}
                }
            ];
        }

        parent::__construct();
    }
}
