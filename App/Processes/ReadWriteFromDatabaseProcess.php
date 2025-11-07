<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Gates\WhatProcessNowGate;
use App\Processes\Tasks\GetDatabaseTask;
use App\Processes\Tasks\ReadFromDatabaseTask;
use App\Processes\Tasks\WriteDataFromDatabaseToFileTask;
use Flows\Processes\Process;

class ReadWriteFromDatabaseProcess extends Process
{

    public function __construct()
    {
        $this->tasks = [
            GetDatabaseTask::class,
            ReadFromDatabaseTask::class,
            WriteDataFromDatabaseToFileTask::class,
            WhatProcessNowGate::class,
        ];
        parent::__construct();
    }
}
