<?php

declare(strict_types=1);

namespace App\Processes\Tasks;

use App\Processes\IO\ResultSetIO;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use LogicException;
use SQLite3;
use SQLite3Stmt;

class ReadFromDatabaseTask implements Task
{
    private SQLite3Stmt $statement;

    public function __invoke(?IO $io = null): ?IO
    {
        $db = $io->get(SQLite3::class, null);
        if (!$db) {
            throw new LogicException('Need connection to database, received NULL');
        }

        $this->statement = $db->prepare('SELECT datetime(), "tbl_A", some FROM tbl_A;');
        $io->set(
            new ResultSetIO($this->statement->execute()),
            ResultSetIO::class
        );

        return $io;
    }

    public function cleanUp(): void
    {
        echo 'PID #' . getmypid() . ' > ';
        echo $this->statement->close()
            ? 'Closed prepared statement'
            : 'Could not close prepared statement';
        echo PHP_EOL;
    }
}
