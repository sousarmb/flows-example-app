<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\IO\ParallelProcessIO;
use App\Services\DateService;
use Collectibles\Contracts\IO;
use Flows\ApplicationKernel;
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
                    if (ApplicationKernel::isOffloadedProcess()) {
                        /* 
                         * Offloaded process errors are logged into a log file 
                         * with the process name, check the logs directory App/Logs
                         * This will send a signal to the main process, which 
                         * can be seen in the main process log file 
                         */
                        function_not_defined_but_call_it_anyway_to_see_effects();
                    }

                    return $data;
                }

                public function cleanUp(): void {}
            },
        ];
        parent::__construct();
    }
}
