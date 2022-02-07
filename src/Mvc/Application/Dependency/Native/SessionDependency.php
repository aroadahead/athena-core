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
            $sessionManager = new SessionManager($config);
        }
        $validators = $this -> sessionConfig -> validators;
        foreach ($validators as $validator) {
            if ($validator -> enabled) {
                $clazz = $validator -> clazz;
                $sessionManager -> getValidatorChain()
                    -> attach('session.validate', array(new $clazz(), 'isValid'));
            }
        }
        $sessionManager -> start();
        Container ::setDefaultManager($sessionManager);
        $container = new Container('initialized');
        if (!isset($container -> init)) {
            $sessionManager -> regenerateId(true);
            $container -> init = 1;
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