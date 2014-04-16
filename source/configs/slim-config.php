<?php
\Slim\Extras\Views\Mustache::$mustacheDirectory = ROOT_PATH . '/libs/Mustache';

return array(
    // Application
    'mode'               => 'product',
    // Debugging
    //'debug'              => DEBUG,
    'debug'              => false,
    // Logging
    'log.writer'         => null,
    'log.level'          => \Slim\Log::DEBUG,
    'log.enabled'        => false,
    // View
    'templates.path'     => ROOT_PATH . '/templates',
    'templates.base'     => 'layout',
    'templates.small'    => 'layout_small',
    'view'               => new \Slim\Extras\Views\Mustache(),
    // Cookies
    'cookies.encrypt'    => true,
    'cookies.lifetime'   => '30 days',
    'cookies.path'       => APP_BASIC_PATH,
    'cookies.secure'     => true,
    'cookies.httponly'   => true,
    // Encryption
    'cookies.secret_key' => APP_SECRET,
);