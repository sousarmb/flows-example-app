<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Gates\GoToDatabaseProcessGate;
use App\Processes\Tasks\CreateFileTask;
use App\Processes\Tasks\WriteDateToFileTask;
use Flows\Processes\Process;

class CreateAndWriteToFileProcess extends Process
{

    public function __construct()
    {
        $this->tasks = [
            CreateFileTask::class,
            WriteDateToFileTask::class,
            GoToDatabaseProcessGate::class
        ];
        parent::__construct();
    }
}
