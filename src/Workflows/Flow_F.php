<?php

declare(strict_types=1);

namespace App\Workflows;

use App\IO\FileResourceIO;
use App\Workflows\Flow_G;
use App\Workflows\Flow_H;
use App\Workflows\Flow_I;
use App\Workflows\Flow_J;
use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Gates\OrGate;
use Flow\Gates\XorGate;
use Flow\Task\Set;

class Flow_F extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "FF-0\n";
                    return new FileResourceIO(
                            fopen('myTextFile', 'a')
                    );
                }

                public function cleanUp(): void {
                    echo "stop-FF-0\n";
                }
            },
            new class extends OrGate {

                public function __invoke(): array {
                    return [
                        Flow_H::class,
                        Flow_I::class,
                        Flow_J::class,
                    ];
                }
            },
            new class extends XorGate {

                public function __invoke(): string {
                    return Flow_G::class;
                }
            },
        ];
        parent::__construct();
    }
}
