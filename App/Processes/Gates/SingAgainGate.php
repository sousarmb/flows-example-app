<?php

declare(strict_types=1);

namespace App\Processes\Gates;

use App\Processes\BranchControlledLoopProcess;
use Flows\Gates\XorGate;

class SingAgainGate extends XorGate
{
    /**
     * @return string Branch flow to this process
     */
    public function __invoke(): string
    {
        return BranchControlledLoopProcess::class;
    }

    /**
     * @param bool $forSerialization
     */
    public function cleanUp(bool $forSerialization = false): void {}
}
