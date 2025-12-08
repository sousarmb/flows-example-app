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
        // (For demonstration purpose only)
        $docs = <<<TEXT
This demo uses a process with 4 tasks.

The first creates the input for the next tasks, the second and third create new
input for the next task, incrementing the counter.

After the second task the process passes a save state sign which makes it save 
its state - position and current IO - in a stack. Before the final task, a
undo state gate reverts the process back to the state stored in the last 
save state sign (its popped out of the stack) and resumes processing from 
there. 

Because it's a gate the developer can use logic to determine how many
"saves" back to go. If the gate returns 0, the process is resumed, not taking
any "saves" back (when there are no more "saves" an exception is thrown and 
processing stops).

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
