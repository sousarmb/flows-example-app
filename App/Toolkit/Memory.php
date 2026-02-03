<?php

declare(strict_types=1);

namespace App\Toolkit;

class Memory {

    /**
     * @param int $size
     * @return string
     * 
     * @example get_human_readable_memory_readout(memory_get_usage())
     * @see https://gist.github.com/mehdichaouch/341a151dd5f469002a021c9396aa2615
     */
    public static function getHumanReadableMemoryUsage(?int $size = null): string {
        if (empty($size)) {
            $size = memory_get_usage();
        }

        $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }
}
