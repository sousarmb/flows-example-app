<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\IO\ParallelProcessIO;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use Flows\Processes\Process;
use Ramsey\Uuid\Uuid;

class ParallelProcessB extends Process
{
    public function __construct()
    {
        $this->tasks = [
            new class implements Task {
                public function __invoke(?IO $io = null): ?IO
                {
                    $data = new ParallelProcessIO(
                        Uuid::uuid4()->toString() . ' PID #' . (int)getmypid(),
                        'But we could set this anyway'
                    );
                    if ($io->has(__CLASS__)) {
                        $io->add($data, __CLASS__);
                    } else {
                        $io->set($data, __CLASS__);
                    }

                    return $io;
                }

                public function cleanUp(): void {}
            },
        ];
        parent::__construct();
    }
}
