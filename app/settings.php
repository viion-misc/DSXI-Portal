<?php
//
// Simple config for the portal
//
return [
    // Game Server
    'server_name' => 'DSXI',

    // Site settings
    'silex_debug' => true,
    'domain' => '.dsxi.server',
    'cookie_expire' => (86400 * 7),

    // Database
    'db_host' => '127.0.0.1',
    'db_name' => 'dsxi',
    'db_user' => 'dsxi',
    'db_pass' => 'dsxi',
];
