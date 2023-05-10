<?php

$mempad = 'anakunda.fr';

use App\Kernel;

require_once '../../vendor/autoload_runtime.php';

return function (array $context) {
    //print_r($context);
    $GLOBALS['q'] = $context['PATH_INFO'] ?? '/';
    $GLOBALS['wroot'] = explode('/index.php', $context['SCRIPT_NAME'])[0] . '/'; //$context['REQUEST_URI']; 
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};