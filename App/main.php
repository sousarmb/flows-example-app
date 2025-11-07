<?php

declare(strict_types=1);

namespace App;

use App\Processes\AfterRandom_AB_GoParallelProcess;
use App\Processes\CreateAndWriteToFileProcess;
use App\Processes\ParallelProcessA;
use App\Processes\ParallelProcessB;
use App\Processes\RandomProcessA;
use App\Processes\RandomProcessB;
use App\Processes\ReadWriteFromDatabaseProcess;
use App\Toolkit\Memory;
use Flows\ApplicationKernel;
use Flows\Registries\ProcessRegistry;

require __DIR__ . '/../vendor/autoload.php';

$app = new ApplicationKernel();
$app->setProcessRegistry(
        (new ProcessRegistry())
                ->add(AfterRandom_AB_GoParallelProcess::class)
                ->add(CreateAndWriteToFileProcess::class)
                ->add(ParallelProcessA::class)
                ->add(ParallelProcessB::class)
                ->add(RandomProcessA::class)
                ->add(RandomProcessB::class)
                ->add(ReadWriteFromDatabaseProcess::class)
);

$return = $app->processProcess(CreateAndWriteToFileProcess::class, null);

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


