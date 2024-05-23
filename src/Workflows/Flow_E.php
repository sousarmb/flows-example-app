<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use App\IO\SpecificIO;

class Flow_E extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "EF-0\n";
                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-EF-0\n";
                }
            },
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "EF-1\n";
                    echo 'EF-0-IO: ' . $io->get('someString') . "\n";
                    echo 'EF-0-IO: ' . $io->get('someInt') . "\n";
                    return new SpecificIO(
                            str_shuffle($io->get('someString')),
                            $io->get('someInt') + 1
                    );
                }

                public function cleanUp(): void {
                    echo "stop-EF-1\n";
                }
            },
        ];
        parent::__construct();
    }
}
