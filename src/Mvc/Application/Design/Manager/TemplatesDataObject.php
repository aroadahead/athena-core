<?php

namespace AthenaCore\Mvc\Application\Design\Manager;

use AthenaPixel\Entity\DesignPackageTemplate;

class TemplatesDataObject extends \Poseidon\Data\DataObject
{
    public function hasTemplate(string $ident): bool
    {
        return $this -> has($ident);
    }

    public function addTemplate(string $ident, DesignPackageTemplate $designPackageTemplate): void
    {
        $this -> set($ident, $designPackageTemplate);
    }
}