<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use Flow\Gates\XorGate;
use App\Workflows\Flow_B;
use App\IO\SpecificIO;

class Flow_C extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "CF-0\n";
                    echo 'CF-0-IO: ' . $io->get('someString') . "\n";
                    echo 'CF-0-IO: ' . $io->get('someInt') . "\n";
                    return new SpecificIO(
                            str_shuffle($io->get('someString')),
                            $io->get('someInt') + 1
                    );
                }

                public function cleanUp(): void {
                    echo "stop-CF-0\n";
                }
            },
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "CF-1\n";
                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-CF-1\n";
                }
            },
            new class extends XorGate {

                public function __invoke(): string {
                    return Flow_B::class;
                }
            },
        ];
        parent::__construct();
    }
}
