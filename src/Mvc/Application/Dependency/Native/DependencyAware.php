<?php

namespace AthenaCore\Mvc\Application\Dependency\Native;

interface DependencyAware
{
    public function init();

    public function setup();

    public function boot();
}