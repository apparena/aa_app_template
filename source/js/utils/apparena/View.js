define([
    'underscore'
], function (_) {

    'use strict';

    var Init, Remove, Instance, ReturnObj, GetInstanze;

    Remove = function () {
        if (!_.isUndefined(_.singleton.view[ReturnObj.namespace])) {
            _.singleton.view[ReturnObj.namespace].stopListening().undelegateEvents().remove();
            delete _.singleton.view[ReturnObj.namespace];
        }
    };

    Init = function (init, id) {
        id = id || 1;

        if (_.isUndefined(_.singleton.view[ReturnObj.namespace])) {
            GetInstanze(id);
        } else {
            if (!_.isUndefined(init) && init === true) {
                Remove();
                GetInstanze(id);
            }
        }

        return Instance();
    };

    GetInstanze = function(id) {
        _.singleton.view[ReturnObj.namespace] = new ReturnObj.code({
            id: 'view_' + ReturnObj.namespace + id
        });
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