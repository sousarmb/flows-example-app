<?php

declare(strict_types=1);

namespace App\Workflows;

use Collectibles\Contracts\IO;
use Flow\Contracts\Task;
use Flow\Task\Set;
use App\IO\ParallelIO;
use App\IO\FileResourceIO;
use App\IO\ResultSetIO;

class Flow_G extends Set {

    public function __construct() {
        $this->tasks = [
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "GF-0\n";
                    $metadata = stream_get_meta_data(
                            $io->get(FileResourceIO::class)->get('fileHandle')
                    );

                    var_dump($metadata);

                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-GF-0\n";
                }
            },
            new class implements Task {

                public function __invoke(?IO $io = null): ?IO {
                    echo "GF-0\n";
                    $fileHandle = $io->get(FileResourceIO::class)->get('fileHandle');
                    for ($i = 0; $i <= 1; $i++) {
                        $statement = $io->get(ResultSetIO::class)[$i]->get('result');
                        fwrite(
                                $fileHandle,
                                implode(', ', $statement->fetchArray(SQLITE3_NUM)) . PHP_EOL
                        );
                    }
                    fclose($fileHandle);
                    return $io;
                }

                public function cleanUp(): void {
                    echo "stop-GF-0\n";
                }
            }];
        parent::__construct();
    }
}
