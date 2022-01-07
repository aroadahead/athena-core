<?php

namespace AthenaCore\Mvc\Application\Config\Loader;

use Laminas\Config\Config;
use Laminas\Config\Reader\Ini;
use Laminas\Config\Reader\Json;
use Laminas\Config\Reader\Xml;
use Laminas\Config\Reader\Yaml;
use phpseclib3\Exception\FileNotFoundException;
use Poseidon\Exception\FileNotReadable;
use Poseidon\Exception\UnsupportedFileType;
use function file_exists;
use function is_readable;
use function pathinfo;
use function strtolower;

class FileLoader
{
    public const TYPE_DIST = 'dist';
    public const TYPE_PHP = 'php';
    public const TYPE_JSON = 'json';
    public const TYPE_YAML = 'yaml';
    public const TYPE_YML = 'yml';
    public const TYPE_INI = 'ini';
    public const TYPE_XML = 'xml';

    public function loadFile(string $file): Config
    {
        if (!is_readable($file)) {
            throw new FileNotReadable("$file is not readable for config loading.");
        }
        $ext = strtolower(pathinfo($file)['extension']);
        return match ($ext) {
            FileLoader::TYPE_DIST => new Config([]),
            FileLoader::TYPE_PHP => new Config(include_once $file),
            FileLoader::TYPE_JSON => new Config((new Json()) -> fromFile($file)),
            FileLoader::TYPE_YAML, FileLoader::TYPE_YML => new Config((new Yaml()) -> fromFile($file)),
            FileLoader::TYPE_INI => new Config((new Ini()) -> fromFile($file)),
            FileLoader::TYPE_XML => new Config((new Xml()) -> fromFile($file)),
            default => throw new UnsupportedFileType("$ext is not a supported config file type.")
        };
    }
}