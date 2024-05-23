<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use App\IO\ResultSetIO;
use SQLite3;

class Flow_I extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "IF-0\n";
                    $db = new SQLite3('mysqlitedb.db');
                    $statement = $db->prepare('SELECT datetime(), "tbl_B", more FROM tbl_B;');
                    return new ResultSetIO(
                            $statement->execute()
                    );
                }

                public function cleanUp(): void {
                    echo "stop-IF-0\n";
                }
            },
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "IF-1\n";
                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-IF-1\n";
                }
            },
        ];
        parent::__construct();
    }
}
