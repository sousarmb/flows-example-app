<?php

declare(strict_types=1);

namespace App;

use App\Processes\NoBranchProcess;
use App\Toolkit\Memory;
use Flows\ApplicationKernel;
use Flows\Registries\ProcessRegistry;

require __DIR__ . '/../vendor/autoload.php';

$app = new ApplicationKernel();
$app->setProcessRegistry(
        (new ProcessRegistry())
                ->add(NoBranchProcess::class)
);

$return = $app->process(NoBranchProcess::class, null);

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
