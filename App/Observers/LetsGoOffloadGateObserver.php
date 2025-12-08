<?php

declare(strict_types=1);

namespace App\Observers;

use Flows\Attributes\Defer\DeferFromFlow;
use Flows\Contracts\Observer;

/*
 * Observations can happen in realtime, deferred from the 
 * process where they happened or from the whole flow.
 * The developer uses attributes in the Flows\Attributes
 * namespace to control this:
 * - DeferFromProcess, observation happens after the process 
 *  where they are triggered is finished
 * - DeferFromFlow, observation happens after all processes
 *  are finished
 * - Realtime, observation occurs when triggered
 */

#[DeferFromFlow()]
class LetsGoOffloadGateObserver implements Observer
{
    public function observe(object $subject): void
    {
        $offloadProcesses = $subject();
        echo 'PID #' . getmypid() . ' > Observed offload AND gate with processes: ' . implode(', ', $offloadProcesses) . PHP_EOL;
    }
}
