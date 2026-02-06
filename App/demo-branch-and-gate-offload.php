<?php

declare(strict_types=1);

namespace App;

use App\Processes\BranchWithAndOffloadGateProcess;
use App\Processes\ParallelProcess_A;
use App\Processes\ParallelProcess_B;
use App\Processes\ParallelProcess_C;
use App\Toolkit\Memory;
use Flows\ApplicationKernel;
use Flows\Registries\ProcessRegistry;

require __DIR__ . '/../vendor/autoload.php';

// (For demonstration purpose only)
$docs = <<<TEXT
Read "demo-branch-and-gate-offload.md" file before running this demo.
TEXT;
echo "\n{$docs}\n\n";
readline("Press return key to continue ...");

$app = new ApplicationKernel();
$app->setProcessRegistry(
        (new ProcessRegistry())
                ->add(ParallelProcess_B::class)
                ->add(ParallelProcess_A::class)
                ->add(BranchWithAndOffloadGateProcess::class)
                ->add(ParallelProcess_C::class)
);

$return = $app->process(BranchWithAndOffloadGateProcess::class, null);

echo "\nDump process flow:\n";
$flow = array_map(
        fn($proc) => get_class($proc),
        $app->getCompletedProcesses()
);
var_dump($flow);
echo "\nDump process output:\n";
var_dump($return);
echo "\nDump total memory usage: \n";
var_dump(Memory::getHumanReadableMemoryUsage());
