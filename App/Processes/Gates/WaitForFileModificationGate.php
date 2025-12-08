<?php

namespace App\Processes\Gates;

use App\Events\TomorrowGateEvent;
use App\Processes\AfterFileModificationProcess;
use App\Processes\DefaultProcess;
use Flows\Gates\EventGate;
use Flows\Gates\Events\FileModificationEvent;

class WaitForFileModificationGate extends EventGate
{
    public function __invoke(): string
    {
        $this->expires = 15;
        $this->pushEvent(
            new FileModificationEvent(
                $this->io->get('fileName'), // Gates have access to previous task output
                1
            ),
            new TomorrowGateEvent(frequency: 1.2)
        );
        $this->waitForEvent();
        return $this->winner instanceof FileModificationEvent
            ? AfterFileModificationProcess::class
            : DefaultProcess::class;
    }

    public function cleanUp(bool $forSerialization = false): void {}
}
