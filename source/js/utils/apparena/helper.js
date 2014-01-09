define(['underscore'], function (_) {
    'use strict';

    return {
        // return locale value on given identifier from global app arena variable
        '__t': function () {
            var num = arguments.length,
                translate, param, text;

            if (num === 0) {
                _.debug.error('No parameters set to translate!');
                return '';
            }

            translate = _.filter(_.aa.locale, {l_id: arguments[0]});

            if (typeof translate !== 'object' || typeof translate[0] === 'undefined') {
                _.debug.warn('Please create a new translation in app wizard for ' + arguments[0].toString());
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
            key = key || 'value';

            if (_.isEmpty(identifier)) {
                _.debug.error('No config parameters set!');
                return false;
            }

            if (!_.isObject(_.aa.config[identifier]) || !_.isString(_.aa.config[identifier][key])) {
                _.debug.warn('Please create a new config value in app wizard for ' + identifier);
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