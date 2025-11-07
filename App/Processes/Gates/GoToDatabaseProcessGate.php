<?php

declare(strict_types=1);

namespace App\Processes\Gates;

use App\Processes\ReadWriteFromDatabaseProcess;
use Flows\Gates\XorGate;

class GoToDatabaseProcessGate extends XorGate
{

    public function __invoke(): string
    {
        return ReadWriteFromDatabaseProcess::class;
    }
}
