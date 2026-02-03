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
