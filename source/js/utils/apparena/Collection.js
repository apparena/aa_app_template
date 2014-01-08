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

    Init = function (init, id) {
        id = id || 1;

        if (_.isUndefined(_.singleton.collection[ReturnObj.namespace])) {
            GetInstanze(id);
        } else {
            if (!_.isUndefined(init) && init === true) {
                Remove();
                GetInstanze(id);
            }
        }

        return Instance();
    };

    GetInstanze = function (id) {
        _.singleton.collection[ReturnObj.namespace] = new ReturnObj.code({
            id: 'collection_' + ReturnObj.namespace + id
        });
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