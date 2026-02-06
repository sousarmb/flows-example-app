<?php

declare(strict_types=1);

namespace App\Events;

use Flows\Event\Event;

readonly class HelloDeferredEvent extends Event
{
    public function __construct(
        private string $greeting
    ) {}
}
