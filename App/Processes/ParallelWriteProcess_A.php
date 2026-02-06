<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class ParallelWriteProcess_A extends Process
{
    public function __construct()
    {
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $result = $io->get('dbconn')->query('SELECT * FROM tbl_A');
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
