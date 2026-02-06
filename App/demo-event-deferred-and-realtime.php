<?php

declare(strict_types=1);

namespace App;

use App\Processes\DeferredAndRealtimeEventsProcess;
use App\Toolkit\Memory;
use Flows\ApplicationKernel;
use Flows\Registries\ProcessRegistry;

require __DIR__ . '/../vendor/autoload.php';

// (For demonstration purpose only)
$docs = <<<TEXT
Read "demo-event-deferred-and-realtime.md" file before running this demo.
TEXT;
echo "\n{$docs}\n\n";
readline("Press return key to continue ...\n");

$app = new ApplicationKernel();
$app->setProcessRegistry(
        (new ProcessRegistry())
                ->add(DeferredAndRealtimeEventsProcess::class)
);

$return = $app->process(DeferredAndRealtimeEventsProcess::class, null);

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
