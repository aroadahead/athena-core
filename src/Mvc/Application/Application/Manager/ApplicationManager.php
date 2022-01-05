<?php

namespace AthenaCore\Mvc\Application\Application\Manager;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;

abstract class ApplicationManager
{
    protected ApplicationCore $applicationCore;

    /**
     * @return ApplicationCore
     */
    public function getApplicationCore(): ApplicationCore
    {
        return $this -> applicationCore;
    }

    /**
     * @param ApplicationCore $applicationCore
     */
    public function setApplicationCore(ApplicationCore $applicationCore): void
    {
        $this -> applicationCore = $applicationCore;
    }

    abstract public function setup():void;

    abstract public function init():void;

    abstract public function boot():void;

}