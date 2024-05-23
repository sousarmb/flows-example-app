<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use App\IO\ResultSetIO;
use SQLite3;

class Flow_J extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "JF-0\n";
                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-JF-0\n";
                }
            },
        ];
        parent::__construct();
    }
}
