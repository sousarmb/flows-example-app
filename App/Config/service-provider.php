<?php

use App\Contracts\GreetContract;
use App\Services\DateService;
use App\Services\Providers\GreetServiceProvider;

return [
    // a concrete implementation
    DateService::class,
    /* An abstraction that resolves to a concrete implementation
     *
     * need_class_that_implements_this_contract => provider_class_that_returns_contract_concrete_implementation
     */
    GreetContract::class => GreetServiceProvider::class
];
