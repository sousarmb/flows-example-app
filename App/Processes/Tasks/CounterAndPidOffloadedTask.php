<?php

declare(strict_types=1);

namespace App\Processes\Tasks;

use App\Processes\IO\OffloadedIO;
use Collectibles\Contracts\IO as IOContract;
use Flows\Contracts\Tasks\Task as TaskContract;

class CounterAndPidOffloadedTask implements TaskContract
{
    public function __invoke(?IOContract $io = null): ?IOContract
    {
        return new OffloadedIO(
            getmypid(),
            $io->get('counter') + 1,
        );
    }

    public function cleanUp(bool $forSerialization = false): void {}
}
