<?php

namespace AthenaCore\View\Helper;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;
use Laminas\View\Helper\AbstractHelper;
use Poseidon\Poseidon;
use Psr\Container\ContainerInterface;

class ViewHelper extends AbstractHelper
{
    protected ApplicationCore $applicationCore;

    public function __construct(protected ContainerInterface $container)
    {
        $this->applicationCore = $this->container->get('core');
    }

    public function getCore():ApplicationCore
    {
        return $this->applicationCore;
    }
}