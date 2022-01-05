<?php

namespace AthenaCore\Mvc\Application\Application\Core;

class ManagerManifest
{
    public static function getManagerManifest():array
    {
        return ['environment', 'filesystem', 'log', 'cache', 'config', 'db', 'design', 'user',
            'laminas', 'api','modules'];
    }
}