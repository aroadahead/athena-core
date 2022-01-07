<?php

namespace AthenaCore\Mvc\Application\Config\Loader;

use _PHPStan_27bc69bf9\Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function array_push;
use function array_walk;
use function is_string;
use function natsort;

class DirectoryLoader
{
    public function load(string $path): array
    {
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