<?php

declare(strict_types=1);

namespace App\Processes\Gates;

use Flows\Gates\FuseGate;

class AnyBottlesLeftGate extends FuseGate
{
    /**
     * @return bool Stop flow
     */
    public function __invoke(): bool
    {
        /* Bottle count >= 0 means there are still bottles left on the wall so the loop continues.
         * Once the bottle count reaches 0 (or less) the process is complete and the loop/flow stops. */
        return $this->io->get('bottleCount') >= 0;
    }

    /**
     * @param bool $forSerialization
     */
    public function cleanUp(bool $forSerialization = false): void {}
}
