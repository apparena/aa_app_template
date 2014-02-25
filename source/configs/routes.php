<?php
return array(
    '/'                            => array(
        'get'    => 'Main:missingId',
        'post'   => 'Ajax:missingId',
        'put'    => 'Ajax:missingId',
        'delete' => 'Ajax:missingId',
    ),
    '/expired/'                    => 'Main:expired',
    '/browser/'                    => 'Main:browser',
    '/:i_id/assets/css/:filename/' => 'Assets:css',
    '/:i_id/assets/js/:filename/'  => 'Assets:js',
    '/:i_id/'                      => 'Main:missingLanguage',
    '/:i_id/:lang/'                => 'Main',
);