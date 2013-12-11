define(['underscore'], function (_) {
    'use strict';

    return {
        // return locale value on given identifier from global app arena variable
        '__t': function (identifier) {
            if (typeof _.aa.locale[identifier] === 'undefined') {
                return identifier.toString();
            }
            return _.aa.locale[identifier];
        },

        // return config value or config key on given identifier from global app arena variable
        '__c': function (identifier, key) {
            if(typeof key === 'undefined') {
                key = 'value';
            }

            if(typeof _.aa.config[identifier][key] === 'undefined') {
                return false;
            }
            return _.aa.config[identifier][key];
        }
    };
});