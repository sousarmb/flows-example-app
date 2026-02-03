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
