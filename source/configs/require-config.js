//var requirejsElem = document.getElementById('requirejs');

var require = {
    waitSeconds: 60,

    /*
     // uncomment only in develope mode! this make problems in r.js
     urlArgs: (function () {
     // add cache busting for development
     return !!(requirejsElem.getAttribute('data-devmode') | 0)
     ? 'bust=' + Date.now()
     : '';
     })(),
     */

    paths: {
        'jquery':            'vendor/jquery/jquery',
        'underscore':        'vendor/underscore-amd/underscore',
        'backbone':          'vendor/backbone-amd/backbone',
        'bootstrap':         'vendor/bootstrap/dist/js/bootstrap',

        // vendor extensions and helper functions
        'text':              'vendor/requirejs-text/text',
        'underscore.string': 'vendor/underscore.string/lib/underscore.string',
        'localstorage':      'vendor/backbone.localStorage/backbone.localStorage',
        'aa_helper':         'utils/apparena/helper',
        'debug':             'vendor/javascript-debug/ba-debug',

        // directory settings
        'templates':         '../templates',
        'modulesSrc':        '../modules',
        'rootSrc':           '../js'
    },

    shim: {
        'bootstrap': {
            deps:    [ 'jquery' ],
            exports: 'bootstrap'
        },

        'underscore.string': {
            deps: ['underscore']
        },

        'debug': {
            exports: 'debug'
        }
    }
};
