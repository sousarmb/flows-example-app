<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use Flow\Gates\XorGate;
use App\Workflows\Flow_D;
use App\Workflows\Flow_E;
use App\IO\SpecificIO;

class Flow_B extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "BF-0\n";
                    echo 'BF-0-IO: ' . $io->get('someString') . "\n";
                    echo 'BF-0-IO: ' . $io->get('someInt') . "\n";
                    return new SpecificIO(
                            str_shuffle($io->get('someString')),
                            $io->get('someInt') + 1
                    );
                }

                public function cleanUp(): void {
                    echo "stop-BF-0\n";
                }
            },
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "BF-1\n";
                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-BF-1\n";
                }
            },
            new class extends XorGate {

                public function __invoke(): string {
                    return (int) date('s') % 2 === 0 ? Flow_D::class : Flow_E::class;
                }
            },
        ];
        parent::__construct();
    }
}
