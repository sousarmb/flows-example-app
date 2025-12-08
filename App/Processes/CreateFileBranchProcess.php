<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Facades\Config;
use Flows\Gates\XorGate;
use Flows\Processes\Process;
use RuntimeException;

class CreateFileBranchProcess extends Process
{
    public function __construct()
    {
        // (For demonstration purpose only)
        $docs = <<<TEXT
This demo uses a process with 1 task. 

The task creates a file in the App directory. The process the branches.\n\n
TEXT;
        echo __CLASS__ . ": $docs";
        readline("Press return key to continue ...\n");
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $fileName = Config::getApplicationDirectory() . 'demo-text-file';
                    $fileHandle = fopen($fileName, 'w+');
                    if (!$fileHandle) {
                        throw new RuntimeException("Could not write demo file");
                    }

                    return new readonly class($fileHandle, $fileName) extends IO {
                        public function __construct(
                            protected mixed $fileHandle,
                            protected string $fileName
                        ) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class extends XorGate {
                public function __invoke(): string
                {
                    return WriteToFileWithAndGateProcess::class;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            }
        ];
        return parent::__construct();
    }
}
