<?php

namespace AthenaCore\Mvc\Application\Laminas\Manager\Exception;

use Laminas\Mvc\I18n\Translator;
use Psr\Container\ContainerInterface;
use function vsprintf;

class ExceptionManager
{
    public function __construct(protected ContainerInterface $container)
    {

    }

    public function throwException(string $class, string $msg, array $args): void
    {
        throw new $class(vsprintf($this -> translate($msg), $args));
    }

    private function translate(string $to): string
    {
        return $this -> translator() -> translate($to);
    }

    private function translator(): Translator
    {
        return $this -> container -> get('MvcTranslator');
    }
}