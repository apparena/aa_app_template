define([
    'underscore'
], function (_) {

    'use strict';

    var Init, Remove, Instance, ReturnObj;

    Remove = function () {
        if (!_.isUndefined(_.singleton.view[ReturnObj.namespace])) {
            _.singleton.view[ReturnObj.namespace].stopListening().undelegateEvents().remove();
            delete _.singleton.view[ReturnObj.namespace];
        }
    };

    Init = function (init) {
        if (_.isUndefined(_.singleton.view[ReturnObj.namespace])) {
            _.singleton.view[ReturnObj.namespace] = new ReturnObj.code();
        } else {
            if (!_.isUndefined(init) && init === true) {
                Remove();
                _.singleton.view[ReturnObj.namespace] = new ReturnObj.code();
            }
        }

        return _.singleton.view[ReturnObj.namespace];
    };

    Instance = function () {
        return _.singleton.view[ReturnObj.namespace];
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