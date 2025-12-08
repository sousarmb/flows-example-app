<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use Flows\Attributes\Lazy;
use Flows\Attributes\Singleton;

/*
 * Services are stored in the service container, the container 
 * prepares the services according to attributes set by the 
 * developer:
 * - Lazy(false|true)
 * -- false, service is instantiated on container boot
 * -- true, service is only instantiated when needed
 * - Singleton(true|false)
 * -- false, service is instantiated every time it is taken
 *  from the container 
 * -- true, service is instantiated once and that same 
 *  instance is returned every time from the container
 */

#[Lazy(false)]
#[Singleton(true)]
class DateService
{
    private Carbon $carbon;

    public function __construct()
    {
        /*
         * The factory does not support ReflectionUnionType 
         * so Carbon cannot be type-hinted in the constructor (yet)
         */
        $this->carbon = new Carbon();
    }

    public function now(): string
    {
        return $this->carbon->now()->format('Y-m-d H:i:s');
    }

    public function today(): string
    {
        return $this->carbon->now()->format('Y-m-d');
    }
}
