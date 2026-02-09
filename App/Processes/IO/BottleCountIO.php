<?php

namespace App\Processes\IO;

use Collectibles\IO;

readonly class BottleCountIO extends IO
{
    public function __construct(
        protected int $bottleCount
    ) {}
}
