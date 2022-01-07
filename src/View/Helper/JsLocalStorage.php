<?php

namespace AthenaCore\View\Helper;

use function is_float;
use function is_int;

class JsLocalStorage extends ViewHelper
{
    public function __invoke():string
    {
        $core = $this->getCore();
        $jsStorage = $core->getUserManager()->getAllJsLocalStorageItems();
        $js='<script>'.PHP_EOL;
        $js.='const store=window.localStorage;'.PHP_EOL;
        foreach($jsStorage as $k=>$v){
            if(is_int($v)||is_float($v)){
                $js.='store.setItem(\''.$k.'\','.$v.');'.PHP_EOL;
            } else {
                $js.='store.setItem(\''.$k.'\',\''.$v.'\');'.PHP_EOL;
            }
        }
        $js.='</script>';
        return $js;
    }
}