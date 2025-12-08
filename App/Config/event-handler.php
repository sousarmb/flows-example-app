<?php

use App\Events\Handlers\HelloDeferredEventHandler;
use App\Events\Handlers\HelloRealtimeEventHandler;
use App\Events\HelloDeferredEvent;
use App\Events\HelloRealtimeEvent;

return [
    HelloRealtimeEvent::class => HelloRealtimeEventHandler::class,
    HelloDeferredEvent::class => HelloDeferredEventHandler::class
];
