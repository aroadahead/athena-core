<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Design\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaPixel\Entity\DesignPackage;
use AthenaPixel\Entity\DesignPackageTemplate;
use AthenaPixel\Model\DesignPackage as DesignPackageModel;
use Exception;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\RendererInterface;
use function file_exists;
use function implode;
use function is_file;

class DesignManager extends ApplicationManager
{
    /**
     * Load JS Skins Auto Config Node
     *
     * @var string
     */
    private const CONFIG_NODE_LOAD_JS_SKINS_AUTO = 'package.load_js_skins_auto';

    /**
     * Load CSS Skins Auto Config Node
     *
     * @var string
     */
    private const CONFIG_NODE_LOAD_CSS_SKINS_AUTO = 'package.load_css_skins_auto';

    /**
     * Use Redis Template Resolve Config Node
     *
     * @var string
     */
    private const CONFIG_NODE_USE_REDIS_TEMPLATE_RESOLVE = 'package.use_redis_for_template_resolving';

    /**
     * Use DB Resolve Config Node
     *
     * @var string
     */
    private const CONFIG_NODE_USE_DB_RESOLVE = 'package.resolve_templates_by_database';

    /**
     * Use Directory Resolve Config Node
     *
     * @var string
     */
    private const CONFIG_NODE_DIR_RESOLVE = 'package.auto_resolve_templates_by_dir';

    /**
     * Layout PHTML Filename
     *
     * @var string
     */
    private const LAYOUT_PHTML = 'layout.phtml';

    /**
     * Template PHTML Filename
     *
     * @var string
     */
    private const TEMPLATE_PHTML = 'template.phtml';

    protected ?string $baseTemplatesPath = null;
    protected DesignPackage $designPackage;
    protected TemplatesDataObject $availableDPTemplates;

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
        $this -> availableDPTemplates = new TemplatesDataObject();
        $this -> designPackage = DesignPackageModel ::oneByActiveStatus();
        $this -> baseTemplatesPath = $this -> getApplicationCore() -> getFilesystemManager()
            -> getDirectoryPaths() -> facade() -> templates();
        if ($this -> getApplicationCore() -> getEnvironmentManager() -> isDevelopmentEnvironment()) {
            $this -> resolvePath();
        }
    }

    /**
     * Return module design package template file
     *
     * @param string $ident
     * @param string $module
     * @param string $baseTemplate
     * @return string
     */
    public function moduleDesignPackageTemplateFile(string $ident, string $module, string $baseTemplate): string
    {
        if ($this -> hasDesignPackageTemplate($ident)) {
            $optionalFile = $this -> getTemplateIfExists($module, $ident);
            if (!is_bool($optionalFile)) {
                return $this -> getDesignPackageTemplatePath($module, $ident);
            }
        }
        return $this -> getApplicationCore() -> getFilesystemManager()
            -> getDirectoryPaths() -> facade() -> modulePath($module, $baseTemplate);
    }

    /**
     * Return module design package layout file
     *
     * @param string $ident
     * @param string $module
     * @return string|null
     */
    public function moduleDesignPackageLayoutFile(string $ident, string $module): string|null
    {
        if ($this -> hasDesignPackageTemplate($ident . "/layout")) {
            $optionalFile = $this -> getTemplateIfExists($module, $ident, true);
            if (!is_bool($optionalFile)) {
                return $this -> getDesignPackageLayoutPath($module, $ident);
            }
        }
        return null;
    }

    /**
     * Return module design package templates stack directory
     *
     * @param string $module
     * @return string
     */
    public function moduleDesignPackageTemplateStackDirectory(string $module): string
    {
        return implode(DIRECTORY_SEPARATOR, array(
            $this -> baseTemplatesPath,
            $this -> getPackageNameBanded(),
            strtolower($module)
        ));
    }

    /**
     * Has Design package template?
     *
     * @param string $identifier
     * @return bool
     */
    private function hasDesignPackageTemplate(string $identifier): bool
    {
        return $this -> availableDPTemplates -> hasTemplate($identifier);
    }

    /**
     * Return template if exists
     *
     * @param string $module
     * @param string $ident
     * @param bool $isLayout
     * @return string|bool
     */
    private function getTemplateIfExists(string $module, string $ident, bool $isLayout = false): string|bool
    {
        if ($isLayout) {
            $file = $this -> getDesignPackageLayoutPath($module, $ident);
        } else {
            $file = $this -> getDesignPackageTemplatePath($module, $ident);
        }
        if (is_file($file) && file_exists($file)) {
            return $file;
        }
        return false;
    }

    /**
     * Return design package layout path
     *
     * @param string $module
     * @param string $ident
     * @return string
     */
    private function getDesignPackageLayoutPath(string $module, string $ident): string
    {
        return $this -> getDesignPackagePathParts($module, $ident, self::LAYOUT_PHTML);
    }

    /**
     * Return design package template path
     *
     * @param string $module
     * @param string $ident
     * @return string
     */
    private function getDesignPackageTemplatePath(string $module, string $ident): string
    {
        return $this -> getDesignPackagePathParts($module, $ident, self::TEMPLATE_PHTML);
    }

    /**
     * Return design package path parts
     *
     * @param string $module
     * @param string $ident
     * @param string $type
     * @return string
     */
    private function getDesignPackagePathParts(string $module, string $ident, string $type): string
    {
        return implode(DIRECTORY_SEPARATOR, array(
            $this -> baseTemplatesPath,
            $this -> getPackageNameBanded(),
            strtolower($module),
            $ident . DIRECTORY_SEPARATOR . $type
        ));
    }

    public function resolvePath(): void
    {
        $dirPath = implode(DIRECTORY_SEPARATOR, array(
            $this -> baseTemplatesPath,
            $this -> getPackageNameBanded()
        ));

        try {
            $di = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath));
            foreach ($di as $path) {
                if (!$path -> isDir()) {
                    $designTemplateFilePath = $path -> getPathname();
                    $designTemplateFilePath = str_ireplace($dirPath, '', $designTemplateFilePath);
                    $parts = explode(DIRECTORY_SEPARATOR, $designTemplateFilePath);
                    $designTemplate = new DesignPackageTemplate();

                    if (!str_contains($designTemplateFilePath, "layout")) {
                        $designTemplate -> setModule($parts[2]);
                        $designTemplate -> setController($parts[3]);
                        $designTemplate -> setAction($parts[4]);
                        $this -> availableDPTemplates -> addTemplate($designTemplate -> getIdentifierString(), $designTemplate);
                    } else {
                        if (count($parts) == 6) {
                            $designTemplate -> setModule($parts[2]);
                            $designTemplate -> setController($parts[3]);
                            $designTemplate -> setAction($parts[4]);
                            $this -> availableDPTemplates -> addTemplate($designTemplate -> getIdentifierString() . "/layout", $designTemplate);
                        } elseif (count($parts) == 5) {
                            $designTemplate -> setModule($parts[2]);
                            $designTemplate -> setController($parts[3]);
                            $this -> availableDPTemplates -> addTemplate($designTemplate -> getControllerIdentifierString() . "/layout", $designTemplate);
                        } elseif (count($parts) == 4) {
                            $designTemplate -> setModule($parts[2]);
                            $this -> availableDPTemplates -> addTemplate($designTemplate -> getModuleIdentifierString() . "/layout", $designTemplate);
                        }
                    }
                }
            }
        } catch (Exception) {

        }
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