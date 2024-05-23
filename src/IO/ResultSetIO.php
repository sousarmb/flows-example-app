<?php

declare(strict_types=1);

namespace App\IO;

use Collectibles\IO;
use SQLite3Result;

readonly class ResultSetIO extends IO {

    public function __construct(
            protected SQLite3Result $result
    ) {
        
    }
}
