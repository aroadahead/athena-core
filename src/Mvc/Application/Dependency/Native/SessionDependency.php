<?php

namespace AthenaCore\Mvc\Application\Dependency\Native;

use Laminas\Config\Config;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;

class SessionDependency extends AbstractDependency implements DependencyAware
{
    protected Config $sessionConfig;

    public function init()
    {
        $sessionManager = null;
        if ($this -> sessionConfig -> use_files) {
            $config = new StandardConfig();
            $config -> setOptions($this -> sessionConfig -> configData -> toArray());
            $config -> setOptions($this -> sessionConfig -> files_handler -> toArray());
            $sessionManager = new SessionManager($config);
            $this -> applicationContainer -> getLogManager() -> debug("SessionManager initialized using " .
                "file storage");
        }
        $validators = $this -> sessionConfig -> validators;
        foreach ($validators as $validator) {
            if ($validator -> enabled) {
                $clazz = $validator -> clazz;
                $sessionManager -> getValidatorChain()
                    -> attach('session.validate', array(new $clazz(), 'isValid'));
                $this -> applicationContainer -> getLogManager() -> debug("Added session validator: $clazz");
            }
        }
        $sessionManager -> start();
        Container ::setDefaultManager($sessionManager);
        $container = new Container('initialized');
        if (!isset($container -> init)) {
            $sessionManager -> regenerateId(true);
            $container -> init = 1;
            $this -> applicationContainer -> getLogManager() -> debug("SessionManager initialized");
        }
    }

    public function setup()
    {
        $this -> sessionConfig = $this -> applicationContainer -> getConfigManager() -> lookup('sessions');
    }

    public function boot()
    {
        // TODO: Implement boot() method.
    }
}