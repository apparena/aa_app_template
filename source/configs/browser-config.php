<?php
/**
 * Define browsers there versions, that are not supported.
 * Example: alle firefox version smaller version 5.
 * Can defined for all, or only for special devices.
 */

return array(
    // checkes only all devices
    'all'     => array(
        'firefox'  => array(
            'version'  => '5',
            'operator' => '<',
        ),
        'msie'     => array(
            'version'  => '8',
            'operator' => '<',
        ),
        'chrome'   => array(
            'version'  => '10',
            'operator' => '<',
        ),
        'opera'    => array(
            'version'  => '8',
            'operator' => '<',
        ),
        'netscape' => array(
            'version'  => '9999',
            'operator' => '<',
        ),
    ),
    // additional checkes only for desktop devices
    'desktop' => array(
        'safari' => array(
            'version'  => '5',
            'operator' => '<',
        ),
    ),
    // additional checkes only for mobile devices
    'mobile'  => array(),
    // additional checkes only for tablet devices
    'tablet'  => array(),
);