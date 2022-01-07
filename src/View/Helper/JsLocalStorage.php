<?php

namespace AthenaCore\View\Helper;

use function array_merge;
use function is_float;
use function is_int;
use function iterator_to_array;

class JsLocalStorage extends ViewHelper
{
    public function __invoke(): string
    {
        $core = $this -> getCore();
        $userJsStorage = iterator_to_array($core -> getUserManager() -> getAllJsLocalStorageItems());
        $envJsStorage = iterator_to_array($core -> getEnvironmentManager() -> getAllJsLocalStorageItems());
        $jsStorage = array_merge($envJsStorage, $userJsStorage);
        $js = '<script>' . PHP_EOL;
        $js .= 'const storage=window.localStorage;' . PHP_EOL;
        foreach ($jsStorage as $k => $v) {
            if (is_int($v) || is_float($v)) {
                $js .= 'storage.setItem(\'' . $k . '\',' . $v . ');' . PHP_EOL;
            } else {
                $js .= 'storage.setItem(\'' . $k . '\',\'' . $v . '\');' . PHP_EOL;
            }
        }
        $js .= '</script>';
        return $js;
    }
}