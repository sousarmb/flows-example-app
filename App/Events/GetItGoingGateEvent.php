<?php

declare(strict_types=1);

namespace App\Events;

use Flows\Gates\Events\HttpEvent;

class GetItGoingGateEvent extends HttpEvent
{
    public function __construct()
    {
        $this->timeout = 60; // seconds
        $this->path = '/get-it-going';
        $this->allowedMethods = ['GET'];
    }

    /**
     * Resolve is called when a request for this resource arrives 
     * 
     * @param string $data JSON encoded string with the request data the HTTP handler server received
     * @return bool TRUE => event wins gate race condition; FALSE => try again when data available on the $data stream
     */
    public function resolve(mixed $data = null): bool
    {
        $request = json_decode($data, true);
        if (
            isset($request['headers']['Authorization'])
            && $request['headers']['Authorization'][0] === 'Bearer 1234'
        ) {
            return $this->accepted(200, "Accepted", "Flow is going to resume");
        }

        return $this->tryAgain(400, "Bad data", "");
    }
}
