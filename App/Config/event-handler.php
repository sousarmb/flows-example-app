<?php

use App\Events\Handlers\HelloWorldEventHandler;
use App\Events\HelloWorldEvent;

return [
    HelloWorldEvent::class => HelloWorldEventHandler::class
];
