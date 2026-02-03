<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class ParallelWriteProcess_C extends Process
{
    public function __construct()
    {
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    // This task allows some of the original IO to continue in the process
                    return new readonly class($io->get('fileHandle'), $io->get('fileName')) extends IO {
                        public function __construct(
                            protected mixed $fileHandle,
                            protected string $fileName
                        ) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            }
        ];

        parent::__construct();
    }
}
