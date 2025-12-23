<?php

declare(strict_types=1);

namespace App\Events;

use Flows\Contracts\Gates\Frequent as FrequentContract;
use Flows\Contracts\Gates\GateEvent as GateEventContract;

class TomorrowGateEvent implements FrequentContract, GateEventContract
{
    /**
     * @var int this time tomorrow
     */
    private int $tomorrow;

    public function __construct(
        /**
         * @var float frequency to check for modifications, in seconds
         */
        private float $frequency = 1.5
    ) {
        $this->tomorrow = strtotime('tomorrow');
    }

    public function resolve($data = null): bool
    {
        // Because tomorrow never comes ;)
        return strtotime('now') >= $this->tomorrow;
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
