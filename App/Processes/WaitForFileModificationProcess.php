<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Gates\WaitForFileModificationGate;
use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Facades\Config;
use Flows\Processes\Process;

class WaitForFileModificationProcess extends Process
{
    public function __construct()
    {
        // (For demonstration purpose only)
        $docs = <<<TEXT
This demo uses a process with 2 tasks.

The first task creates file - App/demo-text-file 

The second task writes "hello world" to the file.

The third step is an event gate with two events: check if file is modified by an external process, check
if tomorrow comes. The first event to happen determines the next process to run / flow path. 

The gate has a timeout of 15 seconds, after that it resolves to the default path set by the developer.\n\n
TEXT;
        echo __CLASS__ . ": $docs";
        readline("Press return key to continue ...\n");
        $this->tasks = [
            new class implements TaskContract {

                private string $fileName;

                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $fhandle = fopen(
                        $this->fileName = Config::getApplicationDirectory() . 'demo-text-file',
                        'w+'
                    );

                    return new readonly class($this->fileName, $fhandle) extends IO {
                        public function __construct(
                            protected string $fileName,
                            protected mixed $fileHandle
                        ) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    fwrite($io->get('fileHandle'), 'hello world' . PHP_EOL);
                    return $io;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            WaitForFileModificationGate::class,
        ];

        parent::__construct();
    }
}
