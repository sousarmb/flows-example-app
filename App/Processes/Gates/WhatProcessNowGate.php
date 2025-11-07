<?php

declare(strict_types=1);

namespace App\Processes\Gates;

use App\Processes\RandomProcessA;
use App\Processes\RandomProcessB;
use Flows\Gates\XorGate;

class WhatProcessNowGate extends XorGate
{

    public function __invoke(): string
    {
        return (int) date('s') % 2 === 0
            ? RandomProcessA::class
            : RandomProcessB::class;
    }
}
