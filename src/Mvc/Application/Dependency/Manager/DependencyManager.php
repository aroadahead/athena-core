<?php

declare(strict_types=1);


namespace AthenaCore\Mvc\Application\Dependency\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Dependency\Native\AbstractDependency;
use AthenaCore\Mvc\Application\Dependency\Native\DependencyAware;

class DependencyManager extends ApplicationManager
{
    protected \SplPriorityQueue $queue;
    protected DepDataObject $dataObject;

    public function setup(): void
    {
        $this -> queue = new \SplPriorityQueue();
        $this -> dataObject = new DepDataObject();
        $deps = $this -> applicationCore -> getConfigManager() -> lookup('dependencies');
        foreach ($deps as $dep => $config) {
            /* @var $clazz AbstractDependency */
            $depClazz = $config -> class;
            $clazz = new $depClazz();
            if (!$clazz instanceof DependencyAware) {
                throw new InvalidDependency("$depClazz does not implement DependencyAware interface");
            }
            $clazz -> setEnabled($config -> enabled);
            $clazz -> setName($dep);
            $clazz -> setApplicationCore($this -> applicationCore);
            $clazz -> setConfig($config -> config);
            $clazz -> setPriority($config -> priority);
            $this -> dataObject -> addDependency($clazz);
            $this -> queue -> insert($clazz, $clazz -> getPriority());
            $this -> applicationCore -> getLogManager() -> debug("Added Dependency $dep to the queue.");
        }

        $tmpQueue = new \SplPriorityQueue();
        if (!$this -> queue -> isEmpty()) {
            $this -> queue -> top();
            while ($this -> queue -> valid()) {
                /* @var $theObject AbstractDependency */
                $theObject = $this -> queue -> current();
                $tmpQueue -> insert($theObject, $theObject -> getPriority());
                if ($theObject -> isEnabled()) {
                    $theObject -> setup();
                    $this -> applicationCore -> getLogManager() -> debug("Dependency {$theObject->getName()} setup.");
                }
                $this -> queue -> next();
            }
        }
        $this -> queue = $tmpQueue;
    }

    public function init(): void
    {
        $tmpQueue = new \SplPriorityQueue();
        if (!$this -> queue -> isEmpty()) {
            $this -> queue -> top();
            while ($this -> queue -> valid()) {
                /* @var $theObject AbstractDependency */
                $theObject = $this -> queue -> current();
                $tmpQueue -> insert($theObject, $theObject -> getPriority());
                if ($theObject -> isEnabled()) {
                    $theObject -> init();
                    $this -> applicationCore -> getLogManager() -> debug("Dependency {$theObject->getName()} initialized.");
                }
                $this -> queue -> next();
            }
        }
        $this -> queue = $tmpQueue;
    }

    public function boot(): void
    {
        if (!$this -> queue -> isEmpty()) {
            $this -> queue -> top();
            while ($this -> queue -> valid()) {
                /* @var $theObject AbstractDependency */
                $theObject = $this -> queue -> current();
                if ($theObject -> isEnabled()) {
                    $theObject -> boot();
                    $this -> applicationCore -> getLogManager() -> debug("Dependency {$theObject->getName()} booted.");
                }
                $this -> queue -> next();
            }
        }
    }
}