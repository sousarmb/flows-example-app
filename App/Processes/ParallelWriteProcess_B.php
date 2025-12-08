<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class ParallelWriteProcess_B extends Process
{
    public function __construct()
    {
        $docs = <<<TEXT
This process is running in parallel with others. 

It will return to the parent process when it is done.\n\n
TEXT;
        echo __CLASS__ . ": $docs";
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $result = $io->get('dbconn')->query('SELECT * FROM tbl_B');
                    while ($row = $result->fetchArray(SQLITE3_NUM)) {
                        fwrite($io->get('fileHandle'), $row[0] . PHP_EOL);
                    }

                    return null;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            }
        ];

        parent::__construct();
    }
}
