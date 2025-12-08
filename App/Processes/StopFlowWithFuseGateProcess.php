<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Gates\FuseGate;
use Flows\Processes\Process;

class StopFlowWithFuseGateProcess extends Process
{
    public function __construct()
    {
        // (For demonstration purpose only)
        $docs = <<<TEXT
This demo uses a process with 4 tasks.

The first creates the input for the next tasks, the second and third create new
input for the next task, incrementing the counter.

After the second task the process, a fuse gate determines wether the flow stops
base on developer logic.

The fourth task prints shows an ending message.\n\n
TEXT;
        echo __CLASS__ . ": $docs";
        readline("Press return key to continue ...\n");
        $this->tasks = [
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    echo "Initial task\n";
                    return new readonly class(1) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    echo "Second task\n";
                    return new readonly class($io->get('counter') + 1) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class extends FuseGate {
                public function __invoke(): bool
                {
                    if (!$blown = (bool) (date('s') % 2)) {
                        echo "Fuse blown\n";
                    }

                    return $blown;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    echo "Third task\n";
                    return new readonly class($io->get('counter') + 1) extends IO {
                        public function __construct(protected int $counter) {}
                    };
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    echo "Final task (fuse didn't blow)\n";
                    return $io;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            }
        ];

        parent::__construct();
    }
}
