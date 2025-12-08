<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Facades\Config;
use Flows\Gates\AndGate;
use Flows\Processes\Process;
use SQLite3;

class WriteToFileWithAndGateProcess extends Process
{
    public function __construct()
    {
        // (For demonstration purpose only)
        $docs = <<<TEXT
This demo uses a process with 2 tasks. 

The first connects to a sqlite database.

Then 3 processes branch from the "parent" process with an And gate. The first two  
write on the file created on the previous process. The third is there to retain 
the original IO in the "parent" process.

The last task show the file contents.\n\n
TEXT;
        echo __CLASS__ . ": $docs";
        readline("Press return key to continue ...\n");
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $dbconn = new SQLite3(Config::getApplicationDirectory() . 'my-sqlite-db');
                    return new readonly class($dbconn, $io->get('fileHandle'), $io->get('fileName')) extends IO {
                        public function __construct(
                            protected SQLite3 $dbconn,
                            protected mixed $fileHandle,
                            protected string $fileName
                        ) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class extends AndGate {
                public function __invoke(): array
                {
                    return [
                        ParallelWriteProcess_A::class,
                        ParallelWriteProcess_B::class,
                        ParallelWriteProcess_C::class
                    ];
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {

                private mixed $fileHandle;

                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $this->fileHandle = $io->get(ParallelWriteProcess_C::class)->get('fileHandle');
                    rewind($this->fileHandle);
                    while ($row = fgets($this->fileHandle)) {
                        echo "$row\n";
                    }

                    return $io;
                }

                public function cleanUp(bool $forSerialization = false): void
                {
                    fclose($this->fileHandle);
                }
            }
        ];
        return parent::__construct();
    }
}
