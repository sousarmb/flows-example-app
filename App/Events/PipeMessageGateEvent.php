<?php

declare(strict_types=1);

namespace App\Events;

use Flows\Gates\Events\StreamReadEvent;

readonly class PipeMessageGateEvent extends StreamReadEvent
{
    public function __construct(string $pipeFile)
    {
        if (!file_exists($pipeFile)) {
            posix_mkfifo($pipeFile, 0600);
        }

        $this->stream = fopen($pipeFile, 'w+'); // Must open in non-blocking mode
    }

    public function resolve($data = null): bool
    {
        return (bool)stream_get_contents($data);
    }
}
