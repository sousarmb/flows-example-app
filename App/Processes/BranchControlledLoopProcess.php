<?php

namespace App\Processes;

use App\Processes\Gates\AnyBottlesLeftGate;
use App\Processes\Gates\SingAgainGate;
use App\Processes\Tasks\DropBottleTask;
use App\Processes\Tasks\SingAlongTask;
use Flows\Processes\Process;


class BranchControlledLoopProcess extends Process
{
    public function __construct()
    {
        $this->tasks = [
            AnyBottlesLeftGate::class,
            SingAlongTask::class,
            DropBottleTask::class,
            SingAgainGate::class,

        ];
        parent::__construct();
    }
}
