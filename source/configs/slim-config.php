<?php
\Slim\Extras\Views\Mustache::$mustacheDirectory = ROOT_PATH . '/libs/Mustache';

$metatags                   = new stdClass();
$metatags->meta_title       = __c('general_title');
$metatags->meta_description = __c('general_desc');
$metatags->meta_canonical   = '';

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
    'view'               => new \Slim\Extras\Views\Mustache(),
    // Cookies
    'cookies.encrypt'    => true,
    'cookies.lifetime'   => '30 days',
    'cookies.path'       => APP_BASIC_PATH,
    'cookies.secure'     => true,
    'cookies.httponly'   => true,
    // Encryption
    'cookies.secret_key' => APP_SECRET,
    // page metatags
    'metatags'           => $metatags,
);