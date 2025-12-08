<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Tasks\CounterAndPidOffloadedTask;
use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\ApplicationKernel;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class ParallelProcess_A extends Process
{
    public function __construct()
    {
        $docs = <<<TEXT
This process is running in parallel with others. 

It will return to the parent process when it is done.\n\n
TEXT;
        echo __CLASS__ . ": $docs";
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
