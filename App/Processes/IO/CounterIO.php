<?php

namespace App\Processes\IO;

use Collectibles\IO;

readonly class CounterIO extends IO
{
    public function __construct(
        protected int $counter
    ) {}
}
