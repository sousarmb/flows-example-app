<?php

declare(strict_types=1);

namespace App\Processes\Tasks;

use App\Processes\IO\FileResourceIO;
use App\Processes\IO\ResultSetIO;
use App\Services\DateService;
use Collectibles\Collection;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use LogicException;

class WriteDataFromDatabaseToFileTask implements Task
{

    public function __construct(
        private DateService $dateService
    ) {}

    public function __invoke(?IO $io = null): ?IO
    {
        $fileResource = $io->get(FileResourceIO::class, null);
        if (null === $fileResource) {
            throw new LogicException('Need file resource, received NULL');
        }

        $resultSet = $io->get(ResultSetIO::class, null);
        if (null === $resultSet) {
            throw new LogicException('Need result set, received NULL');
        }

        while ($row = $resultSet->get('result')->fetchArray(SQLITE3_NUM)) {
            fwrite(
                $fileResource->get('fileHandle'),
                __CLASS__ . ' > PHP time: ' . $this->dateService->now() . ' > DB data ' . implode(', ', $row) . PHP_EOL
            );
        }
        // Return new Collection, all previous IO is lost but it's on purpose,
        // next we're going to offload some processes and some of the resources
        // we were using cannot be serialized (and their purpose is already 
        // fulfilled), just wait for the cleanup
        return new Collection();
    }

    public function cleanUp(): void {}
}
