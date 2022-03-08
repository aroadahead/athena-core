<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Design\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaPixel\Entity\DesignPackage;
use AthenaPixel\Model\DesignPackage as DesignPackageModel;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\RendererInterface;

class DesignManager extends ApplicationManager
{
    protected ?string $baseTemplatesPath = null;
    protected DesignPackage $designPackage;

    public function getDesignPackageId(): int
    {
        return $this -> designPackage -> getId();
    }
    private function getPackageNameBanded(): string
    {
        return implode('/', array(
            $this -> designPackage -> getPackage(),
            $this -> designPackage -> getTheme(),
            $this -> designPackage -> getSkin(),
            $this -> designPackage -> getGroup()
        ));
    }

    public function viewHelperManager(): HelperPluginManager
    {
        return $this -> getApplicationCore() -> container() -> get('ViewHelperManager');
    }

    public function renderer(): RendererInterface
    {
        return $this -> viewHelperManager() -> getRenderer();
    }

    public function setup(): void
    {
        $this -> designPackage = DesignPackageModel ::oneByActiveStatus();
        $this -> baseTemplatesPath = $this -> getApplicationCore() -> getFilesystemManager()
            -> getDirectoryPaths() -> facade() -> templates();
    }

    public function resolvePath(): void
    {

    }

    public function init(): void
    {
        // TODO: Implement init() method.
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }
}