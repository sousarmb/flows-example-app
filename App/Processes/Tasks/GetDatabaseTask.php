<?php

declare(strict_types=1);

namespace App\Processes\Tasks;

use App\Contracts\GreetContract;
use App\Processes\IO\FileResourceIO;
use Collectibles\Collection;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use Flows\Facades\Container;
use SQLite3;

class GetDatabaseTask implements Task
{
    private SQLite3 $db;

    public function __invoke(?IO $io = null): ?IO
    {
        echo Container::get(GreetContract::class, __CLASS__)->greet() . PHP_EOL;

        $collectionIO = new Collection();
        $collectionIO->set($io, FileResourceIO::class);
        $collectionIO->set(
            $this->db = new SQLite3('my-sqlite-db.db', SQLITE3_OPEN_READWRITE),
            SQLite3::class
        );

        return $collectionIO;
    }

    public function cleanUp(): void
    {
        echo 'PID #' . getmypid() . ' > ';
        echo $this->db->close()
            ? 'Closed database connection'
            : 'Could not close database connection';
        echo PHP_EOL;
    }
}
