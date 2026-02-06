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
        $this->tasks = [
            new class implements TaskContract {
                /**
                 * @var SQLite3 $dbconn The database connection
                 */
                private SQLite3 $dbconn;

                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $this->dbconn = new SQLite3(Config::getApplicationDirectory() . 'my-sqlite-db');
                    return new readonly class($this->dbconn, $io->get('fileHandle'), $io->get('fileName')) extends IO {
                        public function __construct(
                            protected SQLite3 $dbconn,
                            protected mixed $fileHandle,
                            protected string $fileName
                        ) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void
                {
                    $this->dbconn->close();
                }
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
