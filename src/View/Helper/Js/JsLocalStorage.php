<?php

namespace AthenaCore\View\Helper\Js;

use AthenaCore\View\Helper\ViewHelper;
use Laminas\Json\Json;
use function array_merge;
use function iterator_to_array;
use const AthenaCore\View\Helper\PHP_EOL;

class JsLocalStorage extends ViewHelper
{
    public function __invoke(): string
    {
        $core = $this -> getCore();
        $userJsStorage = iterator_to_array($core -> getUserManager() -> getAllJsLocalStorageItems());
        $envJsStorage = iterator_to_array($core -> getEnvironmentManager() -> getAllJsLocalStorageItems());
        $configJsStorage = iterator_to_array($core -> getConfigManager() -> facade() -> getAllJsLocalStorageItems());
        $jsStorage = array_merge($configJsStorage, $envJsStorage, $userJsStorage);
        $js = PHP_EOL . '<script>' . PHP_EOL;
        $js .= 'window.localStorage.setItem(\'app:data:persist\',JSON.stringify(' . Json ::encode($jsStorage) . '));';
        $js .= '</script>';
        return $js;
    }
}