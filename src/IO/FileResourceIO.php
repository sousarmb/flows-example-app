<?php

declare(strict_types=1);

namespace App\IO;

use Collectibles\IO;

readonly class FileResourceIO extends IO {

    public function __construct(
            protected mixed $fileHandle
    ) {
        
    }
}
