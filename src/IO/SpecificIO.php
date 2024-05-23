<?php

declare(strict_types=1);

namespace App\IO;

use Collectibles\IO;

readonly class SpecificIO extends IO {

    public function __construct(
            protected string $someString,
            protected int $someInt = 1
    ) {
        
    }
}
