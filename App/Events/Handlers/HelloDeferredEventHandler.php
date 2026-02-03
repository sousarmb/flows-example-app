<?php

declare(strict_types=1);

namespace App\Events\Handlers;

use Flows\Attributes\Defer\DeferFromFlow;
use Flows\Contracts\EventHandler;
use Flows\Event\Event;

/*
 * Events can be handled in realtime, deferred from the 
 * process where they triggered or from the whole flow.
 * The developer uses attributes in the Flows\Attributes
 * namespace to control this:
 * - DeferFromProcess, event is handled after the process 
 *  where it was dispatched is finished
 * - DeferFromFlow, event is handled after all processes
 *  are finished
 * - Realtime, event is handled immediatly after dispatching
 */

#[DeferFromFlow()]
class HelloDeferredEventHandler implements EventHandler
{
    public function handle(Event $event): void
    {
        echo 'A deferred event was triggered: ' . get_class($event) . PHP_EOL;
    }
}
