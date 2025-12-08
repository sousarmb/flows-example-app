<?php

namespace App\Processes\IO;

readonly class OffloadedIO extends CounterIO
{
    public function __construct(
        protected int $pid,
        int $counter
    ) {
        parent::__construct($counter);
    }
}
