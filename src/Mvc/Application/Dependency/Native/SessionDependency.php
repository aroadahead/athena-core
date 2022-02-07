<?php

namespace AthenaCore\Mvc\Application\Dependency\Native;

use Laminas\Config\Config;
use Laminas\Session\Config\SessionConfig;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;
use function sprintf;

class SessionDependency extends AbstractDependency implements DependencyAware
{
    protected Config $sessionConfig;

    public function init()
    {

    }

    public function setup()
    {
        $this -> sessionConfig = $this -> applicationContainer -> getConfigManager() -> lookup('sessions');
        $sessionManager = null;
        if ($this -> sessionConfig -> use_files) {
            $config = new StandardConfig();
            $config -> setOptions($this -> sessionConfig -> configData -> toArray());
            $config -> setOptions($this -> sessionConfig -> files_handler -> toArray());
            $sessionManager = new SessionManager($config);
            $this -> applicationContainer -> getLogManager() -> debug("SessionManager initialized using " .
                "file storage");
        } elseif ($this -> sessionConfig -> use_redis) {
            $config = new SessionConfig();
            $redisConfigs = $this -> applicationContainer -> getEnvironmentManager() -> getRedisConfig();
            $config -> setOptions($this -> sessionConfig -> configData -> toArray());
            $config -> setOptions($this -> sessionConfig -> redis_handler -> toArray());
            $config -> setOptions([
                'phpSaveHandler' => 'redis',
                'savePath' => sprintf("%s://%s:%d?weight=1&timeout=1", $redisConfigs -> scheme,
                    $redisConfigs -> host, $redisConfigs -> port)]);
            $sessionManager = new SessionManager($config);
            $this -> applicationContainer -> getLogManager() -> debug("SessionManager initialized using " .
                "redis storage");
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
        $sessionManager -> regenerateId(true);
        $this -> applicationContainer -> getLogManager() -> debug("SessionManager initialized");
    }

    public function boot()
    {
        // TODO: Implement boot() method.
    }
}