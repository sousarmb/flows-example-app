<?php

declare(strict_types=1);

namespace App\Processes;

use Collectibles\Contracts\IO as IOContract;
use Collectibles\IO;
use DateTime;
use Flows\Contracts\Tasks\Task as TaskContract;
use Flows\Gates\UndoStateGate;
use Flows\Processes\Process;
use Flows\Processes\Sign\SaveState;

class StateChangeWithSaveAndUndoProcess extends Process
{
    public function __construct()
    {
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
            /* Process state is saved here, to come back to this point use 
             * an UndoStateGate */
            SaveState::class,
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
            new class extends UndoStateGate {
                
                public function __invoke(): int
                {
                    echo (new DateTime())->format('H:i:s') . ' | Counter at ' . $this->io->get('counter') . PHP_EOL;
                    sleep(1);
                    return date('s') % 2;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            },
            new class implements TaskContract {
                public function __invoke(?IOContract $io = null): ?IOContract
                {
                    echo "Final task\n";
                    return $io;
                }

                public function cleanUp(bool $forSerialization = false): void {}
            }
        ];

        parent::__construct();
    }
}
