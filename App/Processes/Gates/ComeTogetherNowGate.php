<?php

declare(strict_types=1);

namespace App\Processes\Gates;

use App\Processes\AfterRandom_AB_GoParallelProcess;
use Flows\Gates\XorGate;
use RuntimeException;

class ComeTogetherNowGate extends XorGate
{

    public function __invoke(): string
    {
        /*
         * Gates always have access to the (output) IO instance 
         * from the previous task, the developer can use it to 
         * choose what process to run next or assert the data 
         * and stop the application if necessary
         */
        $previousProcessIO = $this->getIO();
        if (!$previousProcessIO->get('some random string')) {
            throw new RuntimeException('Missing previous process IO');
        }

        return AfterRandom_AB_GoParallelProcess::class;
    }
}
