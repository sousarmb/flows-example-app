<?php

declare(strict_types=1);

namespace App\Processes\IO;

use Collectibles\IO;

readonly class ParallelProcessIO extends IO
{

    public function __construct(
        protected mixed $message,
        protected string $other = 'This is member is here for demonstration purposes, not really used anywhere'
    ) {}
}
