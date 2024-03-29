<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Log\Manager;

use AthenaBridge\Laminas\Config\Config;
use AthenaBridge\Laminas\Log\Filter\Priority;
use AthenaBridge\Laminas\Log\Logger;
use AthenaBridge\Laminas\Log\Writer\Db;
use AthenaBridge\Laminas\Log\Writer\Noop;
use AthenaBridge\Laminas\Log\Writer\Stream;
use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Log\Facade;
use Exception;
use function array_merge;
use function array_walk;

class LogManager extends ApplicationManager
{
    private Logger $logger;
    private Facade $facade;

    /**
     * @throws Exception
     */
    public function setup(): void
    {
        /* @var $config Config */
        $config = $this -> applicationCore -> getConfigManager()
            -> facade() -> logConfig();
        $this -> logger = new Logger();
        $fileConfig = $config -> get('file') -> toArray();
        array_walk($fileConfig, function ($item) {
            if ($item['enabled']) {
                $args = $item['args'];
                $logFile = $this -> applicationCore -> getFilesystemManager()
                    -> getDirectoryPaths() -> facade() -> log($args['stream']);
                $args = array_merge($args, ['stream' => $logFile]);
                $writer = new Stream($args);
                $priority = new Priority($item['priority_level']);
                $writer -> addFilter($priority);
                $this -> logger -> addWriter($writer);
            }
        });
        $dbConfig = $config -> get('db');
        if ($dbConfig -> enabled) {
            $adapter = $this -> applicationCore -> getDbManager() -> masterAdapter();
            $writer = new Db($adapter, $dbConfig -> table_name, $dbConfig -> columnMap -> toArray());
            $priority = new Priority($dbConfig -> priority_level);
            $writer -> addFilter($priority);
            $this -> logger -> addWriter($writer);
        }
        $writer = new Noop();
        $this -> logger -> addWriter($writer);
        $this -> facade = new Facade($this);
    }

    public function emerg(string $msg): void
    {
        $this -> logger -> emerg($msg);
    }

    public function alert(string $msg): void
    {
        $this -> logger -> alert($msg);
    }

    public function crit(string $msg): void
    {
        $this -> logger -> crit($msg);
    }

    public function err(string $msg): void
    {
        $this -> logger -> err($msg);
    }

    public function warn(string $msg): void
    {
        $this -> logger -> warn($msg);
    }

    public function notice(string $msg): void
    {
        $this -> logger -> notice($msg);
    }

    public function info(string $msg): void
    {
        $this -> logger -> info($msg);
    }

    public function debug(string $msg): void
    {
        $this -> logger -> debug($msg);
    }

    public function init(): void
    {

    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    public function facade(): Facade
    {
        return $this -> facade;
    }
}