<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class DefaultProcess extends Process
{
    public function __construct()
    {
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    return $io;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            }
        ];

        parent::__construct();
    }
}
