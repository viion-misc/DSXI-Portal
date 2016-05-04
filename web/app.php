<?php
date_default_timezone_set('UTC');
require_once __DIR__.'/../src/DSXI/Config.php';
require_once __DIR__.'/../vendor/autoload.php';

// get app
(new \DSXI\Routes\SilexApp())->run();
