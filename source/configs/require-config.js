var require = {
    waitSeconds: 60,

    'packages': [
        {
            'name':     'lodash',
            'location': 'vendor/lodash-amd/compat'
        },
        {
            'name':     'underscore',
            'location': 'vendor/lodash-amd/underscore'
        }
    ],

    paths: {
        'jquery':       'vendor/jquery/jquery',
        'backbone':     'vendor/backbone-amd/backbone',
        'bootstrap':    'vendor/bootstrap/dist/js/bootstrap',

        // vendor extensions and helper functions
        'text':         'vendor/requirejs-text/text',
        'localstorage': 'vendor/backbone.localStorage/backbone.localStorage',
        'aa_helper':    'utils/apparena/helper',
        'debug':        'vendor/javascript-debug/ba-debug',
        'jquery.serialize_object': 'utils/jquery/serialize-object',
        'jquery.validator_config': 'utils/jquery/validator-config',

        // directory settings
        'templates':    '../templates',
        'modules':      '../modules',
        'rootSrc':      '../js'
    },

    shim: {
        'bootstrap': {
            deps:    [ 'jquery' ],
            exports: 'bootstrap'
        },

        'debug': {
            exports: 'debug'
        },

        'jquery.serialize_object': {
            deps:    [ 'jquery' ],
            exports: 'jquery'
        },

        'jquery.validator_config': {
            deps:    [ 'jquery' ],
            exports: 'jquery'
        }
    }
};
