<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Gates\LetsGoOffloadGate;
use App\Processes\IO\CounterIO;
use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Processes\Process;

class ObserveAndOffloadGateProcess extends Process
{
    public function __construct()
    {
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    return new readonly class(1) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    return new CounterIO($io->get('counter') + 1);
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            LetsGoOffloadGate::class,
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    $pid = ['parent#PID' => getmypid()];
                    $pid[ParallelProcess_A::class . '#PID'] = $io->get(ParallelProcess_A::class)->get('pid');
                    $pid[ParallelProcess_B::class . '#PID'] = $io->get(ParallelProcess_B::class)->get('pid');
                    $pid[ParallelProcess_C::class . '#PID'] = $io->get(ParallelProcess_C::class)->get('pid');
                    $total = $io->get(ParallelProcess_A::class)->get('counter')
                        + $io->get(ParallelProcess_B::class)->get('counter')
                        + $io->get(ParallelProcess_C::class)->get('counter');
                    return new readonly class($total, $pid) extends IO {
                        public function __construct(
                            protected int $counter,
                            protected array $pid
                        ) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
        ];

        parent::__construct();
    }
}
