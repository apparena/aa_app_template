define(['underscore'], function (_) {
    'use strict';

    return {
        // return locale value on given identifier from global app arena variable
        '__t': function () {
            var num = arguments.length,
                translate, param, text;

            if (num === 0) {
                return '';
            }

            translate = _.filter(_.aa.locale, {l_id: arguments[0]});

            if (typeof translate !== 'object') {
                return arguments[0].toString();
            }

            text = translate[0].value;

            if (num > 1) {
                delete arguments[0];

                param = _.values(arguments);

                text = _.sprintf(text, param);
            }

            return text;
        },

        // return config value or config key on given identifier from global app arena variable
        '__c': function (identifier, key) {
            if (typeof key === 'undefined') {
                key = 'value';
            }

            if (typeof _.aa.config[identifier][key] === 'undefined') {
                return false;
            }
            return _.aa.config[identifier][key];
        },

        'sprintf': function () {
            var key = 1;
            return arguments[0].replace(/%((%)|s)/g, function (response) {
                return response[2] || arguments[key++]
            });
        }
    };
});