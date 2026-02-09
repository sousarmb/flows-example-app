<?php

namespace App\Processes\Tasks;

use Collectibles\Contracts\IO as IOContract;
use Flows\Contracts\Tasks\Task as TaskContract;

class SingAlongTask implements TaskContract
{
    /**
     * @param IOContract|null $io
     * @return IOContract|null
     */
    public function __invoke(?IOContract $io = null): ?IOContract
    {
        $bottleCount = $io->get('bottleCount');
        $plural = $bottleCount === 1 ? 'bottle' : 'bottles';
        if ($bottleCount === 0) {
            $verse = "No more bottles of beer on the wall, no more bottles of beer.\nGo to the store and buy some more.\n";
        } else {
            $verse = sprintf(
                "%s %s of beer on the wall, %s %s of beer.\n",
                $io->get('bottleCount'),
                $plural,
                $io->get('bottleCount'),
                $plural
            );
        }
        echo $verse;
        return $io;
    }

    public function cleanUp(bool $forSerialization = false): void {}
}
