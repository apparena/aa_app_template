<?php
/**
 * define all routes for slim here
 * the router checks all routes from the top to the bottom
 * if a route matches, the rest are ignored
 */
return array(
    '/'                                 => array(
        'get'    => 'Main:missingId',
        'post'   => 'Ajax:missingId',
        'put'    => 'Ajax:missingId',
        'delete' => 'Ajax:missingId',
    ),
    '/expired/'                         => 'Main:expired',
    '/browser/'                         => 'Main:browser',
    '/error/'                           => 'Main:notFound',
    '/cache/'                           => 'Cache',
    '/:i_id/cache/'                     => 'Cache:instance',
    '/:i_id/assets/css/:filename/'      => 'Assets:css',
    '/:i_id/:lang/assets/js/:filename/' => 'Assets:js',
    '/:i_id/:lang/share/:base/'         => 'Share',
    '/:i_id/'                           => 'Main:missingLanguage',
    '/:i_id/:lang/'                     => 'Main',
);