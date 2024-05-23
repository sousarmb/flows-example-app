<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use App\IO\SpecificIO;

class Flow_D extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "DF-0\n";
                    echo 'DF-0-IO: ' . $io->get('someString') . "\n";
                    echo 'DF-0-IO: ' . $io->get('someInt') . "\n";
                    return new SpecificIO(
                            str_shuffle($io->get('someString')),
                            $io->get('someInt') + 1
                    );
                }

                public function cleanUp(): void {
                    echo "stop-DF-0\n";
                }
            },
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "DF-1\n";
                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-DF-1\n";
                }
            },
        ];
        parent::__construct();
    }
}
