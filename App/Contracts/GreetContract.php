<?php

declare(strict_types=1);

namespace App\Contracts;

interface GreetContract
{
    public function greet(?string $name = null): string;
}
