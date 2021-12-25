<?php

namespace AthenaCore;

class Module
{
    public function getConfig():array
    {
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }
}