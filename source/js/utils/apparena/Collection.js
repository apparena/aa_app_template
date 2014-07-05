define([
    'underscore'
], function (_) {

    'use strict';

    var Init, Remove, Instance, ReturnObj, GetInstanze;

    /**
     * destroy collection objects and eventlistener
     *
     * @static
     *
     * @example
     *  Collection().remove();
     *
     * @return void
     */
    Remove = function () {
        if (!_.isUndefined(_.singleton.collection[ReturnObj.namespace])) {
            _.singleton.collection[ReturnObj.namespace].stopListening();
            delete _.singleton.collection[ReturnObj.namespace];
        }
    };

    /**
     * returns an object from Instance()/singleton object
     *
     * @static
     *
     * @example
     *  Collection().init({id : 123});
     *  Collection().init({attributes : {model : model.auth}});
     *
     * @param {Object} settings Not required - JSON string with settings for attributes and Collection id
     * @return {Object} CallExpression
     */
    Init = function (settings) {
        settings = settings || {};
        var attributes = settings.attributes || {},
            init = settings.init || false;

        attributes.id = settings.id || 1;
        ReturnObj.namespace = ReturnObj.namespace + attributes.id;

        if (_.isUndefined(_.singleton.collection[ReturnObj.namespace])) {
            GetInstanze(attributes);
        } else {
            if (init === true) {
                Remove();
                GetInstanze(attributes);
            }
        }

        return Instance();
    };

    /**
     * Creates a new instance and store them into Collection singleton object
     *
     * @static
     *
     * @param {Object} attributes Not required - JSON string with settings for attributes and Collection id
     *
     * @return void
     */
    GetInstanze = function (attributes) {
        _.singleton.collection[ReturnObj.namespace] = new ReturnObj.code(attributes);
    };

    /**
     * returns an instance from global singleton storage
     *
     * @static
     *
     * @example
     *  Collection().getInstance();
     *
     * @return {Object} MemberExpression
     */
    Instance = function () {
        return _.singleton.collection[ReturnObj.namespace];
    };

    /**
     * The ReturnObj class stores functionality to extend a AMD module,
     * that handles initialization and removing objects of a Backbone Collection.
     * Namespace and code must be set by the AMD module.
     *
     * @requires {String} namespace, {Object} code
     * @type {{init: Init, code: null, namespace: string, remove: Remove, getInstance: Instance}}
     */
    ReturnObj = {
        init:        Init,
        code:        null,
        namespace:   '',
        remove:      Remove,
        getInstance: Instance
    };

    return ReturnObj;
});