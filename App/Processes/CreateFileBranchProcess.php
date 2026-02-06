<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Tasks\CreateDemoTextFileTask;
use Flows\Gates\XorGate;
use Flows\Processes\Process;

class CreateFileBranchProcess extends Process
{
    public function __construct()
    {
        $this->tasks = [
            CreateDemoTextFileTask::class,
            new class extends XorGate {
                public function __invoke(): string
                {
                    return WriteToFileWithAndGateProcess::class;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            }
        ];
        return parent::__construct();
    }
}
