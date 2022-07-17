<?php
var_dump($_REQUEST);
var_dump($_SERVER);
if(php_sapi_name() === 'cli'){
    if(!empty($argv)){
        var_dump($_REQUEST);
    }
}