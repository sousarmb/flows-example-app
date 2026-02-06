<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class AfterFileModificationProcess extends Process
{
    public function __construct()
    {
        $this->tasks = [
            new class implements TaskContract {
                protected IO $io;
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    rewind($io->get('fileHandle'));
                    echo $io->get('fileName') . ':' . fread($io->get('fileHandle'), 8192);
                    return $this->io = $io;
                }

                public function cleanUp(bool $forSerialization = false): void
                {
                    fclose($this->io->get('fileHandle'));
                    unlink($this->io->get('fileName'));
                }
            },
        ];

        parent::__construct();
    }
}
