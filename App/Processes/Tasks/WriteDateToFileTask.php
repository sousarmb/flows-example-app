<?php

declare(strict_types=1);

namespace App\Processes\Tasks;

use App\Events\HelloWorldEvent;
use App\Processes\IO\FileResourceIO;
use App\Services\DateService;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use Flows\Event\Kernel as EventKernel;
use LogicException;

class WriteDateToFileTask implements Task
{
    public function __construct(
        private DateService $dateService,
        private EventKernel $events
    ) {}

    public function __invoke(?IO $io = null): ?IO
    {
        if (!$io instanceof FileResourceIO) {
            throw new LogicException('Need file resource');
        }

        $fileHandle = $io->get('fileHandle');
        fwrite($fileHandle, __CLASS__ . ' > ' . $this->dateService->now() . PHP_EOL);
        // Events can be triggered anywhere
        $this->events->handle(new HelloWorldEvent('hey dude'));

        return $io;
    }

    public function cleanUp(): void {}
}
