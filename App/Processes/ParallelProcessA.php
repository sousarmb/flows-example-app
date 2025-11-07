<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\IO\ParallelProcessIO;
use App\Services\DateService;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use Flows\Facades\Container;
use Flows\Processes\Process;

class ParallelProcessA extends Process
{
    public function __construct()
    {
        $this->tasks = [
            /*
             * Tasks can also be defined as anonymous classes
             */
            new class implements Task {
                public function __invoke(?IO $io = null): ?IO
                {
                    $dateService = Container::get(DateService::class);
                    $data = new ParallelProcessIO(
                        $dateService->now() . ' PID #' . (int)getmypid()
                    );

                    return $data;
                }

                public function cleanUp(): void {}
            },
        ];
        parent::__construct();
    }
}
