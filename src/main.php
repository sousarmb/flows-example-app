<?php

use Flow\Task\Registry;
use Flow\Kernel;
use App\Workflows;
use App\Toolkit\Memory;

require __DIR__ . '/../vendor/autoload.php';

$taskSets = (new Registry())
        ->add(new Workflows\Flow_A())
        ->add(new Workflows\Flow_B())
        ->add(new Workflows\Flow_C())
        ->add(new Workflows\Flow_D())
        ->add(new Workflows\Flow_E())
        ->add(new Workflows\Flow_F())
        ->add(new Workflows\Flow_G())
        ->add(new Workflows\Flow_H())
        ->add(new Workflows\Flow_I())
        ->add(new Workflows\Flow_J());

$kernel = new Kernel($taskSets);
$return = $kernel->processTaskSet(
        Workflows\Flow_F::class,
        null
);
echo "\nDump process flow: \n";
var_dump(array_map(
                fn($flow) => get_class($flow),
                $kernel->getProcessedTaskSetsFlow()
        )
);
echo "\nDump process output: \n";
var_dump($return);
echo "\nDump total memory usage: \n";
var_dump(Memory::getHumanReadableMemoryUsage());
