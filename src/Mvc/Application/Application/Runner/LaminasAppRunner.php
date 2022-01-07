<?php

namespace AthenaCore\Mvc\Application\Application\Runner;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;
use Laminas\Mvc\Application;

class LaminasAppRunner extends ApplicationCore
{

    public function deploy()
    {
        $this -> userManager -> addJsLocalStorageItem('locale', 'en_US');
        $this -> userManager -> addJsLocalStorageItem('pubKey', '6asd68d68ddd6saadd79asd7das79ads9');
        $this -> environmentManager -> addJsLocalStorageItem('version', '0.0.1');
        $this -> environmentManager -> addJsLocalStorageItem('dist', 'shard');
        $this -> configManager -> facade() -> addJsLocalStorageItem('configItem', 'someVal');

        $path = $this -> getFilesystemManager() -> getDirectoryPaths() -> facade() -> configLaminas();
        $appConfig = require $path . '/application.config.php';
        Application ::init($appConfig) -> run();
    }
}