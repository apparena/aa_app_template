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
        'jquery':                  'vendor/jquery/dist/jquery',
        'backbone':                'vendor/backbone-amd/backbone',
        'bootstrap':               'vendor/bootstrap/dist/js/bootstrap',

        // vendor extensions and helper functions
        'text':                    'vendor/requirejs-text/text',
        'localstorage':            'vendor/backbone.localStorage/backbone.localStorage',
        'aa_helper':               'utils/apparena/helper',
        'debug':                   'vendor/javascript-debug/ba-debug',
        'jquery.validation':       'vendor/jquery.validation/dist/jquery.validate',
        'jquery.serialize_object': 'utils/jquery/serialize-object',
        'jquery.validator_config': 'utils/jquery/validator-config',
        'jMD5':                    'utils/jquery/jquery.md5',

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

        'jMD5': {
            deps:    ['jquery'],
            exports: 'jquery'
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