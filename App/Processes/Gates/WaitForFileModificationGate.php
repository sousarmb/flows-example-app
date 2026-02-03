<?php

namespace App\Processes\Gates;

use App\Events\GetItGoingGateEvent;
use App\Events\PipeMessageGateEvent;
use App\Events\TomorrowGateEvent;
use App\Processes\AfterFileModificationProcess;
use App\Processes\DefaultProcess;
use Flows\Gates\EventGate;
use Flows\Gates\Events\FileModificationEvent;

class WaitForFileModificationGate extends EventGate
{
    public function __construct()
    {
        /* After this time has passed the gate stops waiting for events and 
         * calls __invoke() to determine where to branch the flow */
        $this->expires = 60; // seconds
    }

    public function registerEvents(): void
    {
        $this
            ->pushEvent(
                new FileModificationEvent(
                    $this->io->get('fileName'), // Gates have access to previous task output
                    1
                )
            )
            ->pushEvent(
                new TomorrowGateEvent(frequency: 1.23)
            )
            ->pushEvent(
                new PipeMessageGateEvent('/tmp/myfifo') // This gate event represents a pipe write by an external process
            )
            ->pushEvent(new GetItGoingGateEvent());
    }

    public function __invoke(): string
    {
        return $this->winner instanceof FileModificationEvent
            ? AfterFileModificationProcess::class
            : DefaultProcess::class;
    }

    public function cleanUp(bool $forSerialization = false): void
    {
        parent::cleanUp(); // Always run the parent clean up
    }
}
