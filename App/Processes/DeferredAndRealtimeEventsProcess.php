<?php

declare(strict_types=1);

namespace App\Processes;

use App\Events\HelloDeferredEvent;
use App\Events\HelloRealtimeEvent;
use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Facades\Events as EventsKernel;
use Flows\Processes\Process;

class DeferredAndRealtimeEventsProcess extends Process
{
    public function __construct()
    {
        // (For demonstration purpose only)
        $docs = <<<TEXT
This demo uses a process with 3 tasks.

The first creates the input for the next tasks, the second and third create 
new input for the next task, incrementing the counter.

The first task dispatches a "defer from flow" event, handled when the flow 
stops (all processes are finished).

The second task dispatches a real time event, handled immediatly.\n\n
TEXT;
        echo __CLASS__ . ": $docs";
        readline("Press return key to continue ...\n");
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    EventsKernel::handle(new HelloDeferredEvent('Hello World!'));
                    return new readonly class(1) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    EventsKernel::handle(new HelloRealtimeEvent('Hello World!'));
                    return new readonly class($io->get('counter') + 1) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    return new readonly class($io->get('counter') + 2) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            }
        ];

        parent::__construct();
    }
}
