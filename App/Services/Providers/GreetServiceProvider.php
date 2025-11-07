<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Contracts\GreetContract;
use App\Processes\Tasks\CreateFileTask;
use App\Services\HelloGalaxyService;
use App\Services\HelloWorldService;
use Flows\Attributes\Lazy;
use Flows\Attributes\Singleton;
use Flows\Container\Caller;
use Flows\Contracts\Container\ServiceProvider;

/*
 * Services providers are stored in the service container, 
 * the container prepares the services according to attributes 
 * set by the developer:
 * - Lazy(false|true)
 * -- false, service is instantiated on container boot
 * -- true, service is only instantiated when needed
 * - Singleton(true|false)
 * -- false, service is instantiated every time it is taken
 *  from the container 
 * -- true, service is instantiated once and that same 
 *  instance is returned every time from the container
 */

#[Lazy(true)]
#[Singleton(false)]
class GreetServiceProvider implements ServiceProvider
{
    public function __invoke(?Caller $caller = null): GreetContract
    {
        return $caller->get() === CreateFileTask::class
            ? new HelloWorldService()
            : new HelloGalaxyService();
    }
}
