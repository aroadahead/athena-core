<?php

namespace AthenaCore\Mvc\Application\Config\Loader;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function array_push;
use function array_walk;
use function is_string;
use function natsort;

class DirectoryLoader
{
    public function load(string $path): array
    {
        $files = [];
        $di = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($di as $p) {
            if (!$p -> isDir()) {
                array_push($files, $p -> getPathname());
            }
        }
        natsort($files);
        return $files;
    }
}