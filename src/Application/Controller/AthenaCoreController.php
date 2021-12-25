<?php

declare(strict_types=1);

namespace AthenaCore\Application\Controller;

use AthenaCore\Application\Service\AthenaCoreService;
use AthenaCore\Mvc\Controller\MvcController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Application module controller
 */
class AthenaCoreController extends MvcController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function applicationService(): AthenaCoreService
    {
        return $this->invokeService();
    }
}
