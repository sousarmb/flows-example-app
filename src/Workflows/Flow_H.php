<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use App\IO\ResultSetIO;
use SQLite3;

class Flow_H extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "HF-0\n";
                    $db = new SQLite3('mysqlitedb.db');
                    $statement = $db->prepare('SELECT datetime(), "tbl_A", some FROM tbl_A;');
                    return new ResultSetIO(
                            $statement->execute()
                    );
                }

                public function cleanUp(): void {
                    echo "stop-HF-0\n";
                }
            },
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "HF-1\n";
                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-HF-1\n";
                }
            },
        ];
        parent::__construct();
    }
}
