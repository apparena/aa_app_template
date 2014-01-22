define([
    'underscore'
], function (_) {

    'use strict';

    var Init, Remove, Instance, ReturnObj, GetInstanze;

    Remove = function () {
        if (!_.isUndefined(_.singleton.model[ReturnObj.namespace])) {
            _.singleton.model[ReturnObj.namespace].stopListening();
            delete _.singleton.model[ReturnObj.namespace];
        }
    };

    Init = function (settings) {
        settings = settings || {};

        var init = settings.init || false;

        if (_.isUndefined(_.singleton.model[ReturnObj.namespace])) {
            GetInstanze(settings);
        } else {
            if (init === true) {
                Remove();
                GetInstanze(settings);
            }
        }

        return Instance();
    };

    GetInstanze = function (settings) {
        settings = settings || {};

        var attributes = settings.attributes || {};
        attributes.id = settings.id || 1;

        _.singleton.model[ReturnObj.namespace] = new ReturnObj.code(attributes);
    };

    Instance = function () {
        return _.singleton.model[ReturnObj.namespace];
    };

    ReturnObj = {
        init:        Init,
        code:        null,
        namespace:   '',
        remove:      Remove,
        getInstance: Instance
    };

    return ReturnObj;
});