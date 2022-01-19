<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Log\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Log\Facade;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use Laminas\Config\Config;
use Laminas\Log\Filter\Priority;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Db;
use Laminas\Log\Writer\Noop;
use Laminas\Log\Writer\Stream;
use function array_merge;
use function array_walk;
use function file_exists;
use function rename;

class LogManager extends ApplicationManager
{
    private Logger $logger;
    private Facade $facade;

    /**
     * @throws Exception
     */
    public function setup(): void
    {

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
        /* @var $config Config */
        $config = $this -> applicationCore -> getConfigManager()
            -> lookup('log');
        $this -> logger = new Logger();
        $fileConfig = $config -> get('file') -> toArray();
        array_walk($fileConfig, function ($item) {
            if ($item['enabled']) {
                $args = $item['args'];
                $args = array_merge($args, ['stream' => $this -> logFile($args['stream'], $item['rotate_by_day'])]);
                $writer = new Stream($args);
                $priority = new Priority($item['priority_level']);
                $writer -> addFilter($priority);
                $this -> logger -> addWriter($writer);
            }
        });
        $dbConfig = $config -> get('db');
        if ($dbConfig -> enabled) {
            $adapter = $this -> applicationCore -> getDbManager() -> masterAdapter();
            $writer = new Db($adapter, $dbConfig -> table_name, $dbConfig -> columnMap);
            $priority = new Priority($dbConfig -> priority_level);
            $writer -> addFilter($priority);
            $this -> logger -> addWriter($writer);
        }
        $writer = new Noop();
        $this -> logger -> addWriter($writer);
        $this->logger->info("Logging info message.");
        $this->logger->debug("Logging debug message.");
        $this -> facade = new Facade($this);
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    public function facade(): Facade
    {
        return $this -> facade;
    }

    /**
     * @throws Exception
     */
    private function logFile(string $file, bool $rotateByDay = false): string
    {
        $logFile = $this -> applicationCore -> getFilesystemManager()
                -> getDirectoryPaths() -> facade() -> log()
            . DIRECTORY_SEPARATOR . $file;
        if ($rotateByDay) {
            $tz = $this -> applicationCore -> getConfigManager() -> lookup('i18n.timezone');
            $today = new DateTime('NOW', new DateTimeZone($tz));
            $yesterday = $today -> sub(new DateInterval("P1D"));
            $yesterdayDate = $yesterday -> format('Ymd');
            $yesterdayFile = $logFile . '.' . $yesterdayDate;
            if (!file_exists($yesterdayFile) && file_exists($logFile)) {
                rename($logFile, $yesterdayFile);
            }
        }
        return $logFile;
    }
}