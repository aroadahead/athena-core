<?php

namespace AthenaCore\Mvc\Application\Config\Lookup;

use AthenaBridge\Laminas\Config\Config;
use function explode;
use function trim;

class NodeLookup
{
    protected ConfigCache $cache;
    protected string $separator = '.';
    protected bool $isCustomSeparator = false;

    public function __construct(protected Config $masterConfig)
    {
        $this -> cache = new ConfigCache();
    }

    public function setSeparator(string $separator): void
    {
        $this -> separator = $separator;
        if ($this -> separator !== '.') {
            $this -> isCustomSeparator = true;
        }
    }

    public function set(string $node, mixed $data): void
    {
        $node = trim($node);
        $parts = explode('.', $node);
        $len = count($parts);
        $idx = 0;
        $ret = $this -> masterConfig;
        foreach ($parts as $part) {
            if ($idx == ($len - 1)) {
                $ret -> $part = $data;
            } else {
                $ret -> $part = new Config([], true);
                $ret = $ret -> $part;
                $idx++;
            }
        }
    }

    public function descend(string $node = null, mixed $default = null): mixed
    {
        $node = trim($node);
        if (empty($node)) {
            return $this -> masterConfig;
        }
        if ($this -> cache -> configExists($node)) {
            return $this -> cache -> getItem($node);
        }
        $ret = $this -> masterConfig;
        $parts = explode($this -> separator, $node);
        foreach ($parts as $part) {
            if ($ret instanceof Config) {
                $ret = $ret -> getOrFail($part, $default);
            }
            $this -> cache -> configStore($node, $ret);
        }
        return $ret;
    }
}