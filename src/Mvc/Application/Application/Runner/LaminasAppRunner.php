<?php

namespace AthenaCore\Mvc\Application\Application\Runner;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;
use AthenaCore\Mvc\Application\Laminas\StandardContainer;
use Laminas\Mvc\Application;
use Poseidon\Poseidon;

class LaminasAppRunner extends ApplicationCore
{

    public function deploy()
    {
        $this -> userManager -> addJsLocalStorageItem('locale', $this -> environmentManager -> getDefaultLocale());
        $this -> userManager -> addJsLocalStorageItem('pubKey', '6asd68d68ddd6saadd79asd7das79ads9');
        $this -> environmentManager -> addJsLocalStorageItem('version', $this -> environmentManager -> getVersionNumber());
        $this -> environmentManager -> addJsLocalStorageItem('dist', $this -> environmentManager -> getVersionName());
        $this -> configManager -> facade() -> addJsLocalStorageItem('configItem', 'someVal');

        $appConfig = $this -> configManager -> lookup('laminas.application', true);
        $appConfig['modules'] = $this -> configManager -> lookup('laminas.modules', true);
        $app = Application ::init($appConfig);

        $this -> setContainer($app -> getServiceManager());
        StandardContainer ::setPsrContainer($app -> getServiceManager());
        Poseidon ::registry() -> add('container', $app -> getServiceManager());

        $app -> run();
    }
}