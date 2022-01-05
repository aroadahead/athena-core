<?php

namespace AthenaCore\Mvc\Application\Application\Runner;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;
use Laminas\Mvc\Application;

class LaminasAppRunner extends ApplicationCore
{

    public function deploy()
    {
        $rootPath = $this->getRootPath();
        $appConfig = require $rootPath.'/config/application.config.php';
        Application::init($appConfig)->run();
    }
}