<?php

namespace AthenaCore\View\Helper;

use Laminas\Json\Json;
use function array_merge;
use function iterator_to_array;

class JsLocalStorage extends ViewHelper
{
    public function __invoke(): string
    {
        $core = $this -> getCore();
        $userJsStorage = iterator_to_array($core -> getUserManager() -> getAllJsLocalStorageItems());
        $envJsStorage = iterator_to_array($core -> getEnvironmentManager() -> getAllJsLocalStorageItems());
        $jsStorage = array_merge($envJsStorage, $userJsStorage);
        $js = PHP_EOL . '<script>' . PHP_EOL;
        $js .= 'window.localStorage.setItem(\'app:data:persist\',JSON.stringify(' . Json ::encode($jsStorage) . '));';
        $js .= '</script>';
        return $js;
    }
}