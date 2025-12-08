<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\GreetContract;

/*
 * If no timing attributes are set, the container treats these services
 * as lazy and not singleton. Remember: services must be registered in 
 * the container for timing attributes to have effect, otherwise these 
 * are plain old PHP classes
 */

class HelloGalaxyService implements GreetContract
{
    public function greet(?string $name = null): string
    {
        return 'Hello galaxy ' . $name ?: 'stranger' . '!';
    }
}
