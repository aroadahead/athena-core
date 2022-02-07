<?php

namespace AthenaCore\Mvc\Application\Dependency\Manager;

use AthenaCore\Mvc\Application\Dependency\Native\AbstractDependency;

class DepDataObject extends \Poseidon\Data\DataObject
{
    /**
     * Add Dependency
     *
     * @param AbstractDependency $dependency
     */
    public function addDependency(AbstractDependency $dependency)
    {
        $this -> set($dependency -> getName(), $dependency);
    }

    /**
     * Return Dependency
     *
     * @param string $name
     * @return mixed
     */
    public function getDependency(string $name): AbstractDependency
    {
        return $this -> get($name);
    }
}