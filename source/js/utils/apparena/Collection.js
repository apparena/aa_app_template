define([
    'underscore'
], function (_) {

    'use strict';

    var Init, Remove, Instance, ReturnObj, GetInstanze;

    Remove = function () {
        if (!_.isUndefined(_.singleton.collection[ReturnObj.namespace])) {
            _.singleton.collection[ReturnObj.namespace].stopListening().undelegateEvents().remove();
            delete _.singleton.collection[ReturnObj.namespace];
        }
    };

    Init = function (settings) {
        var settings = settings || {},
            init = settings.init || false;

        if (_.isUndefined(_.singleton.collection[ReturnObj.namespace])) {
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
        var settings = settings || {},
            id = settings.id || 1,
            attributes = settings.attributes || {};

        attributes.id = 'collection_' + ReturnObj.namespace + id;

        _.singleton.collection[ReturnObj.namespace] = new ReturnObj.code(attributes);
    };

    Instance = function () {
        return _.singleton.collection[ReturnObj.namespace];
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