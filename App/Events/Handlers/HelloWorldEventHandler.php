<?php

declare(strict_types=1);

namespace App\Events\Handlers;

use Flows\Attributes\Defer\DeferFromProcess;
use Flows\Contracts\EventHandler;
use Flows\Event\Event;

/*
 * Events can be handled in realtime, deferred from the 
 * process where they triggered or from the whole flow.
 * The developer uses attributes in the Flows\Attributes
 * namespace to control this:
 * - DeferFromProcess, observation happens after the process 
 *  where they are triggered is finished
 * - DeferFromFlow, observation happens after all processes
 *  are finished
 * - Realtime, observation occurs when triggered
 */

#[DeferFromProcess()]
class HelloWorldEventHandler implements EventHandler
{
    public function handle(Event $event): void
    {
        echo 'An event was triggered: ' . get_class($event) . PHP_EOL;
    }
}
