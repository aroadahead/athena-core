<?php

namespace AthenaCore\Mvc\Application\Config\Loader;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function array_push;
use function in_array;
use function natsort;

class DirectoryLoader
{
    public function load(string $path, array $excludeRootPaths = []): array
    {
        $files = [];
        $di = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($di as $p) {
            if ($p -> isDir()) {
                if (in_array($p -> getFilename(), $excludeRootPaths)) {
                    continue;
                }
            }
            if (!$p -> isDir()) {
                array_push($files, $p -> getPathname());
            }
        }
        natsort($files);
        return $files;
    }
}