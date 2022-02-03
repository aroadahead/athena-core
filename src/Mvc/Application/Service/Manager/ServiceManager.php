<?php

namespace AthenaCore\Mvc\Application\Service\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;

class ServiceManager extends ApplicationManager
{
    protected AwsManager $awsManager;

    public function __construct()
    {
        $this -> awsManager = new AwsManager();
    }

    public function setup(): void
    {
        // TODO: Implement setup() method.
    }

    public function init(): void
    {
        $this -> awsManager -> setConfigManager($this -> applicationCore -> getConfigManager());
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    public function aws(): AwsManager
    {
        return $this -> awsManager;
    }
}