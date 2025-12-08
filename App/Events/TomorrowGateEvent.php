<?php

declare(strict_types=1);

namespace App\Events;

use DateTime;
use Flows\Contracts\Gates\Frequent as FrequentContract;
use Flows\Contracts\Gates\GateEvent as GateEventContract;

class TomorrowGateEvent implements FrequentContract, GateEventContract
{
    public function __construct(
        /**
         * @var DateTime this time tomorrow
         */
        private DateTime $tomorrow = new DateTime('+1 day'),
        /**
         * @var float frequency to check for modifications, in seconds
         */
        private float $frequency = 1.5
    ) {}

    public function resolve($data = null): bool
    {
        // Because tomorrow never comes ;)
        return $this->tomorrow
            ->diff(new DateTime('now'))
            ->format('%a') === '0';
    }

    public function getFrequency(): float
    {
        return $this->frequency;
    }

    public function setFrequency(float $milliseconds): void
    {
        $this->frequency = $milliseconds;
    }
}
