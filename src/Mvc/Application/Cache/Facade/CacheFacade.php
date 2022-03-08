<?php

namespace AthenaCore\Mvc\Application\Cache\Facade;

use AthenaBridge\Laminas\Serializer\Adapter\PhpSerialize;
use AthenaCore\Mvc\Application\Cache\Manager\CacheManager;
use http\Exception\InvalidArgumentException;
use Predis\Client;
use function is_float;
use function is_int;
use function is_null;

class CacheFacade
{
    protected int $dbIdx = 0;
    protected PhpSerialize $serialize;
    protected ?Client $client = null;

    public function __construct(protected CacheManager $manager)
    {
        $this -> serialize = new PhpSerialize();
        $this -> client = $this -> manager -> client();
    }

    public function generateRandomKey(): string
    {
        return $this -> client -> randomkey();
    }

    public function keys(string $prefix = "*"): array
    {
        return $this -> client -> keys($prefix);
    }

    public function renameKey(string $old, string $new): void
    {
        $this -> client -> rename($old, $new);
    }

    public function renameNx(string $old, string $new): void
    {
        $this -> client -> renamenx($old, $new);
    }

    public function move(string $key, int $db): void
    {
        $this -> client -> move($key, $db);
    }

    public function hmset(string $key, array $val): void
    {
        $this -> client -> hmset($key, $val);
    }

    public function hdel(string $key, array $val): void
    {
        $this -> client -> hdel($key, $val);
    }

    public function hgetall(array $raw): array
    {
        return $this -> client -> hgetall($raw);
    }

    public function setDbIndex(int $idx): void
    {
        $this -> dbIdx = $idx;
    }

    public function getDbIndex(): int
    {
        return $this -> dbIdx;
    }

    public function incr(string $key, $by = null): void
    {
        if (is_null($by)) {
            $this -> client -> incr($key);
        } else {
            if (is_int($by)) {
                $this -> client -> incrby($key, $by);
            } elseif (is_float($by)) {
                $this -> client -> incrbyfloat($key, $by);
            } else {
                throw new InvalidArgumentException("invalid redis increment by");
            }
        }
    }

    public function setDataAsArrayOrObject(string $key, mixed $value): void
    {
        $this -> setData($key, $this -> serialize -> serialize($value));
    }


    public function removeIfExists(string $key): void
    {
        if ($this -> hasData($key)) {
            $this -> removeData($key);
        } else {
            $this -> manager -> getApplicationCore() -> getLogManager() -> debug("Redis key $key not found and not deleted.");
        }
    }

    public function removeData(string $key): void
    {
        $this -> client -> del($key);
        $this -> manager -> getApplicationCore() -> getLogManager() -> debug("Deleted redis key $key");
    }

    public function setData(string $key, mixed $value, int $ttl = null): void
    {
        if ($ttl === null) {
            $this -> client -> set($key, $value);
        } else {
            $this -> client -> setex($key, $ttl, $value);
        }
    }

    public function getDataAsArrayOrObject(string $key): mixed
    {
        return $this -> serialize -> unserialize($this -> getData($key));
    }

    public function getData(string $key): mixed
    {
        return $this -> client -> get($key);
    }


    public function hasData(string $key): bool
    {
        if ($this -> client -> exists($key)) {
            return true;
        }
        return false;
    }

    public function decr(string $key, int $by = null): void
    {
        if (is_null($by)) {
            $this -> client -> decr($key);
        } else {
            if (is_int($by)) {
                $this -> client -> decrby($key, $by);
            } else {
                throw new InvalidArgumentException("invalid redis decrement by. expecting int only.");
            }
        }
    }

    public function expire(string $key, int $secs): void
    {
        $this -> client -> expire($key, $secs);
    }

    public function expireAt(string $key, int $timestamp): void
    {
        $this -> client -> expireat($key, $timestamp);
    }

    public function switchDb(int $db): void
    {
        $this -> client -> select($db);
        $this -> setDbIndex($db);
    }
}