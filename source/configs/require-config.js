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
        //'facebook':          '//connect.facebook.net/en_US/all',

        // vendor extensions and helper functions
        'text':              'vendor/requirejs-text/text',
        'underscore.string': 'vendor/underscore.string/lib/underscore.string',
        'localstorage':      'vendor/backbone.localStorage/backbone.localStorage',
        'aa_helper':         'utils/apparena/helper',
        'validation':        'vendor/backbone-validation/src/backbone-validation-amd',
        'jqvalidation':      'vendor/jquery.validation/jquery.validate',
        //'jqBootstrapValidation':   'vendor/jqBootstrapValidation/jqBootstrapValidation', // save it for later, thats a very good validation plugin
        'logger':            '../modules/logging/js/views/LoggerView',
        'pnotify':           '../modules/notification/js/libs/jquery/jquery.pnotify',
        'snow': 'utils/jquery/jsnow.min',
        'debug':             'vendor/javascript-debug/ba-debug',
        'google_api':        '//apis.google.com/js/client:plus',

        // directory settings
        'templates':         '../templates',
        'modulesSrc':        '../modules',
        'rootSrc':           '../js',
        'units':             '../units',

        // unit testing
        //'QUnit':        '//code.jquery.com/qunit/qunit-git',
        'QUnit':             'vendor/qunit/qunit/qunit',
        //'sinon':        '//sinonjs.org/releases/sinon-1.7.3.js',
        'sinon':             'vendor/sinon/lib/sinon',
        'sinon-ie':          '//sinonjs.org/releases/sinon-ie-1.7.3.js',
        //'sinon-qunit':  '//sinonjs.org/releases/sinon-qunit-1.0.0.js',
        'sinon-qunit':       'vendor/sinon-qunit/pkg/sinon-qunit-1.0.0',
        'tests':             '../tests/units'
    },

    shim: {
        'QUnit': {
            exports: 'QUnit',
            init:    function () {
                QUnit.config.autoload = false;
                QUnit.config.autostart = false;
            }
        },

        'bootstrap': {
            deps:    [ 'jquery' ],
            exports: 'bootstrap'
        },

        'underscore.string': {
            deps: ['underscore']
        },

        'sinon-qunit': {
            deps:    [ 'sinon', 'sinon-ie' ],
            exports: 'sinon'
        },

        /*'jqBootstrapValidation': {
         deps:    [ 'jquery' ],
         exports: 'jqBootstrapValidation'
         },*/

        'jqvalidation': {
            deps:    [ 'jquery' ],
            exports: 'jqvalidation'
        },

        /*'facebook': {
         exports: 'FB'
         },*/

        'pnotify': {
            deps:    ['jquery'],
            exports: 'pnotify'
        },

        'snow': {
            deps:    ['jquery'],
            exports: 'snow'
        },

        'debug': {
            exports: 'debug'
        },

        'google_api': {
            exports: 'gapi'
        }
    }
};
