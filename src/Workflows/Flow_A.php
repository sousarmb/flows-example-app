<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use Flow\Gates\XorGate;
use App\Workflows\Flow_C;
use App\IO\SpecificIO;

class Flow_A extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "AF-0\n";
                    return new SpecificIO(
                            'some random string',
                            1
                    );
                }

                public function cleanUp(): void {
                    echo "stop-AF-0\n";
                }
            },
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "AF-1\n";
                    echo 'AF-1-IO: ' . $io->get('someString') . "\n";
                    echo 'AF-1-IO: ' . $io->get('someInt') . "\n";
                    return new SpecificIO(
                            str_shuffle($io->get('someString')),
                            $io->get('someInt') + 1
                    );
                }

                public function cleanUp(): void {
                    echo "stop-AF-1\n";
                }
            },
            new class extends XorGate {

                public function __invoke(): string {
                    return Flow_C::class;
                }
            },
        ];
        parent::__construct();
    }
}
