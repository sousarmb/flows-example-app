<?php

use App\Observers\LetsGoOffloadGateObserver;
use App\Processes\Gates\LetsGoOffloadGate;

/*
 * This array stores the subject => observer pair used for observations,
 * whenever the subject is passed to the observation kernel, the observer
 * class is instantiated and passed it
 */ 

return [
    LetsGoOffloadGate::class => LetsGoOffloadGateObserver::class
];
