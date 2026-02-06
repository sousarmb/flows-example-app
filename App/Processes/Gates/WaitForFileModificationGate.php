<?php

namespace App\Processes\Gates;

use App\Events\GetItGoingGateEvent;
use App\Events\PipeMessageGateEvent;
use App\Events\TomorrowGateEvent;
use App\Processes\AfterFileModificationProcess;
use App\Processes\DefaultProcess;
use Flows\Facades\Config;
use Flows\Gates\EventGate;
use Flows\Gates\Events\FileModificationEvent;
use Flows\Gates\Events\SqlResultSetEvent;
use PDO;
use Pdo\Sqlite;
use PDOStatement;

class WaitForFileModificationGate extends EventGate
{
    /**
     * @var Sqlite
     */
    private Sqlite $conn;

    /**
     * @var PDOStatement
     */
    private PDOStatement $pstmt;

    public function __construct()
    {
        $dsn = sprintf('sqlite:%s%s', Config::getApplicationDirectory(), 'my-sqlite-db');
        $this->conn = new PDO($dsn);

        /* After this time has passed the gate stops waiting for events and 
         * calls __invoke() to determine where to branch the flow */
        $this->expires = 60; // seconds
    }

    public function registerEvents(): void
    {
        $this->pstmt = $this->conn->prepare('SELECT some FROM tbl_A WHERE some="?"');

        $this
            ->pushEvent(
                new FileModificationEvent(
                    $this->io->get('fileName'), // Gates have access to previous task output
                    1
                )
            )
            ->pushEvent(
                new TomorrowGateEvent(frequency: 1.23)
            )
            ->pushEvent(
                new PipeMessageGateEvent('/tmp/myfifo') // This gate event represents a pipe write by an external process
            )
            ->pushEvent(new GetItGoingGateEvent())
            ->pushEvent(
                new SqlResultSetEvent(
                    $this->pstmt,
                    ['my-text']
                )
            );
    }

    public function __invoke(): string
    {
        return $this->winner instanceof FileModificationEvent
            ? AfterFileModificationProcess::class
            : DefaultProcess::class;
    }

    public function cleanUp(bool $forSerialization = false): void
    {
        unset($this->pstmt);
        unset($this->conn);
        // Always run the parent clean up
        parent::cleanUp();
    }
}
