<?php

declare(strict_types=1);

namespace App\Processes;

use App\Events\HelloWorldEvent;
use App\Processes\Gates\ComeTogetherNowGate;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use Flows\Event\Kernel as EventKernel;
use Flows\Processes\Process;
use Ramsey\Uuid\Uuid;

class RandomProcessA extends Process
{
    public function __construct(
        private EventKernel $events
    ) {
        $this->tasks = [
            new class implements Task {
                public function __invoke(?IO $io = null): ?IO
                {
                    $io->set(
                        'PID#' . (int)getmypid() . ' >> ' . Uuid::uuid4()->toString(),
                        'some random string'
                    );
                    return $io;
                }

                public function cleanUp(): void {}
            },
            /* 
             * We could go in a different direction now (process wise), 
             * it's not mandatory to return to a common flow but this is a demo ...
             */
            ComeTogetherNowGate::class
        ];
        parent::__construct();
        // Events can be triggered anywhere
        $this->events->handle(new HelloWorldEvent('hey you!'));
    }
}
