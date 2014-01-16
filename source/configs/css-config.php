<?php
/**
 * add css lib paths here to compile it later with a less compiler in init.php
 * path starts from root
 */
$css_import = array(
    'main'                                                   => '/css/style.css',
    '/js/vendor/bootstrap/dist/css/bootstrap.css'            => 'file',
    '/js/vendor/font-awesome/css/font-awesome.css'           => 'file',
    '/modules/notification/css/jquery.pnotify.default.css' => 'file',
    //'css_app' => 'config',
    'css_user'                                               => 'config',
);

// some stuf that replaces after compiling key = search, value = replace
$css_path_replacements = array(
    '{{app_base_color.value}}' => __c('app_base_color'),
    '../fonts/fontawesome'     => '../../js/vendor/font-awesome/fonts/fontawesome',
    '../fonts/glyphicons'      => '../../js/vendor/bootstrap/dist/fonts/glyphicons'
);