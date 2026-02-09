<?php

namespace App\Processes\Tasks;

use App\Processes\IO\BottleCountIO;
use Collectibles\Contracts\IO as IOContract;
use Flows\Contracts\Tasks\Task as TaskContract;

class DropBottleTask implements TaskContract
{
    /**
     * @param IOContract|null $io
     * @return IOContract|null
     */
    public function __invoke(?IOContract $io = null): ?IOContract
    {
        if ($io->get('bottleCount') > 0) {
            $verse = sprintf(
                "Take one down and pass it around, %s %s of beer on the wall.\n\n",
                $io->get('bottleCount') - 1,
                $io->get('bottleCount') - 1 === 1 ? 'bottle' : 'bottles'
            );
            echo $verse;
        }

        return new BottleCountIO($io->get('bottleCount') - 1);
    }

    public function cleanUp(bool $forSerialization = false): void {}
}
