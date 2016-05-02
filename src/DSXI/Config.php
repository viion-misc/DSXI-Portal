<?php

$settings = require_once(__DIR__.'/../../app/settings.php');

//
// Random crap
//
define('ROOT', __DIR__ .'/../..');

// automate settings into defines
// eg: 'domain' => 'dsxi.server', accessed as: DOMAIN
foreach($settings as $key => $value) {
    define(strtoupper($key), $value);
}

// Twig configuration
define('TWIG_CONFIG', [
    'twig.path' => ROOT .'/src/DSXI/Views',
    'twig.options' => [
        'debug' => SILEX_DEBUG
    ],
]);

// Console command configuration
define('CONSOLE_CONFIG', [
    'console.name' => SERVER_NAME,
    'console.version' => '1.0.0',
    'console.project_directory' => ROOT,
]);

//
// Dirty show function, makes print_r pretty
//
function show($data, $vars = [], $noPre = false)
{
    if ($vars) $data = vsprintf($data, $vars);

    if ($noPre) {
        echo print_r($data, true);
        return;
    }

    echo '<pre>'. print_r($data, true) .'</pre>';
}
