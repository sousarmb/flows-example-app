<?php

declare(strict_types=1);

namespace App\Processes\Tasks;

use App\Contracts\GreetContract;
use App\Processes\IO\FileResourceIO;
use Collectibles\Contracts\IO;
use Flows\Contracts\Tasks\Task;
use Flows\Facades\Config;

class CreateFileTask implements Task
{
    private $fileResource;

    public function __construct(GreetContract $greeter)
    {
        echo $greeter->greet() . PHP_EOL;
    }

    public function __invoke(?IO $io = null): ?IO
    {
        $appDirectory = Config::getApplicationDirectory();
        return new FileResourceIO(
            $this->fileResource = fopen($appDirectory . 'my-new-text-file', 'a')
        );
    }

    public function cleanUp(): void {}
}
