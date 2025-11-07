<?php

declare(strict_types=1);

namespace App\Processes;

use App\Processes\Gates\ComeTogetherNowGate;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use Flows\Processes\Process;

class RandomProcessB extends Process
{
    public function __construct()
    {
        $this->tasks = [
            new class implements Task {
                public function __invoke(?IO $io = null): ?IO
                {
                    $io->set(
                        'PID#' . (int)getmypid() . ' >> I\'m not so random!',
                        'some random string'
                    );
                    return $io;
                }

                public function cleanUp(): void {}
            },
            /* 
             * We could go in a different direction now (process wise), 
             * it's not mandatory to return to a common flow but this is a demo ...
             */
            ComeTogetherNowGate::class
        ];
        parent::__construct();
    }
}
