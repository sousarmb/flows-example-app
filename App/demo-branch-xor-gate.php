<?php

declare(strict_types=1);

namespace App;

use App\Processes\BranchWithXorGate_End_Process;
use App\Processes\BranchWithXorGate_Middle_Process;
use App\Processes\BranchWithXorGate_Start_Process;
use App\Toolkit\Memory;
use Flows\ApplicationKernel;
use Flows\Registries\ProcessRegistry;

require __DIR__ . '/../vendor/autoload.php';

$app = new ApplicationKernel();
$app->setProcessRegistry(
        (new ProcessRegistry())
                ->add(BranchWithXorGate_Start_Process::class)
                ->add(BranchWithXorGate_End_Process::class)
                ->add(BranchWithXorGate_Middle_Process::class)
);

$return = $app->process(BranchWithXorGate_Start_Process::class, null);

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
