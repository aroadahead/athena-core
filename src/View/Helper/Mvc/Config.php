<?php

namespace AthenaCore\View\Helper\Mvc;

use AthenaCore\View\Helper\ViewHelper;

class Config extends ViewHelper
{
    public function __invoke(string $node,bool $asArray=false):mixed
    {
        return $this->container->get('conf')->lookup($node,$asArray);
    }
}