var require = {
    waitSeconds: 60,

    'packages': [
        {
            'name':     'lodash',
            'location': 'vendor/lodash/dist',
            'main':     'lodash.underscore'
        },
        {
            'name':     'underscore',
            'location': 'vendor/lodash/dist',
            'main':     'lodash.underscore'

            /*'location': 'vendor/lodash-amd/underscore',
            'main': 'main'*/

        }
    ],

    paths: {
        'jquery':                  'vendor/jquery/jquery',
        'backbone':                'vendor/backbone-amd/backbone',
        'bootstrap':               'vendor/bootstrap/dist/js/bootstrap',

        // vendor extensions and helper functions
        'text':                    'vendor/requirejs-text/text',
        'localstorage':            'vendor/backbone.localStorage/backbone.localStorage',
        'aa_helper':               'utils/apparena/helper',
        'debug':                   'vendor/javascript-debug/ba-debug',
        'jquery.validation':       'vendor/jquery.validation/jquery.validate',
        'jquery.serialize_object': 'utils/jquery/serialize-object',
        'jquery.validator_config': 'utils/jquery/validator-config',

        // directory settings
        'templates':               '../templates',
        'modules':                 '../modules',
        'rootSrc':                 '../js',

        // backbone extending
        'ViewExtend':              'utils/apparena/View',
        'ModelExtend':             'utils/apparena/Model',
        'CollectionExtend':        'utils/apparena/Collection'
    },

    shim: {
        'bootstrap': {
            deps:    ['jquery'],
            exports: 'bootstrap'
        },

        'debug': {
            exports: 'debug'
        },

        'jquery.validator_config': {
            deps:    ['jquery', 'jquery.validation'],
            exports: 'jquery'
        },

        'jquery.validation': {
            deps:    ['jquery'],
            exports: 'jquery'
        },

        'jquery.serialize_object': {
            deps:    ['jquery'],
            exports: 'jquery'
        }
    }
};
