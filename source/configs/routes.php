<?php
/**
 * define all routes for slim here
 * the router checks all routes from the top to the bottom
 * if a route matches, the rest are ignored
 */
return array(
    '/'                                                   => array(
        'get'    => 'Main:missingId',
        'post'   => 'Main:missingId',
        'put'    => 'Ajax:missingId',
        'delete' => 'Ajax:missingId',
    ),
    // old ajax calls with jquery
    '/ajax/'                                              => 'Ajax:index@post',
    // new ajax calls ober backbone models and collections
    '(/:i_id(/:lang))/ajax/(:class/(:method/(:params/)))' => array(
        'get'    => 'Ajax:get',
        'post'   => 'Ajax:post',
        'put'    => 'Ajax:put',
        'delete' => 'Ajax:delete',
    ),
    '/expired/'                                           => 'Main:expired',
    '/browser/'                                           => 'Main:browser',
    '/error/'                                             => 'Main:notFound',
    '/cache/'                                             => 'Cache',
    '/:i_id/cache/'                                       => 'Cache:instance',
    '/:i_id/assets/css/:filename/'                        => 'Assets:css',
    '/:i_id/:lang/assets/js/:filename/'                   => 'Assets:js',
    '/:i_id/:lang/share/:base/'                           => 'Share',
    '/:i_id/'                                             => 'Main:missingLanguage',
    '/:i_id/:lang/'                                       => 'Main',
);