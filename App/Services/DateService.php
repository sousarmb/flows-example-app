<?php

declare(strict_types=1);

namespace App\Services;

use DateTime;
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
    private DateTime $dt;

    public function __construct()
    {
        $this->dt = new DateTime();
    }

    public function now(): string
    {
        return $this->dt->format('Y-m-d H:i:s');
    }

    public function today(): string
    {
        return $this->dt->format('Y-m-d');
    }
}
