<?php

namespace AthenaCore\Mvc\Application\Application\Core;

class ManagerManifest
{
    public static function getManagerManifest():array
    {
        return ['environment', 'filesystem', 'cache', 'config', 'db', 'log','design', 'user',
            'laminas', 'api','modules','service'];
    }
}