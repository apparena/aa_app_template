<?php
return array(
    // Application
    'mode'               => 'product',
    // Debugging
    'debug'              => DEBUG,
    // Logging
    'log.writer'         => null,
    'log.level'          => \Slim\Log::DEBUG,
    'log.enabled'        => true,
    // View
    'templates.path'     => ROOT_PATH . '/templates',
    'view'               => '\Slim\View',
    // Cookies
    'cookies.encrypt'    => true,
    'cookies.lifetime'   => '30 days',
    'cookies.path'       => APP_BASIC_PATH,
    'cookies.secure'     => true,
    'cookies.httponly'   => true,
    // Encryption
    'cookies.secret_key' => APP_SECRET
);