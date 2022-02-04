<?php

namespace AthenaCore\Mvc\Application\Config\Lookup;

use AthenaCore\Mvc\Application\Config\Exception\NodeNotFound;
use Laminas\Config\Config;
use Poseidon\Data\DataObject;
use function explode;
use function trim;

class NodeLookup
{
    protected DataObject $cache;

    public function __construct(protected Config $masterConfig)
    {
        $this -> cache = new DataObject();
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

    public function descend(string $node = null): mixed
    {
        $node = trim($node);
        if (empty($node)) {
            return $this -> masterConfig;
        }
        if ($this -> cache -> hasItem($node)) {
            return $this -> cache -> getItem($node);
        }
        $ret = $this -> masterConfig;
        $parts = explode('.', $node);
        foreach ($parts as $part) {
            if ($ret instanceof Config) {
                if(!$ret->offsetExists($part)){
                    throw new NodeNotFound("config node: $node not found!");
                }
                $ret = $ret -> get($part);
            }
            $this -> cache -> setItem($node, $ret);
        }
        return $ret;
    }
}