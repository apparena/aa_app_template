define([
    'underscore'
], function (_) {

    'use strict';

    var Init, Remove, Instance, ReturnObj, GetInstanze;

    /**
     * destroy model objects and eventlistener
     *
     * @module Remove
     * @requires Backbone.Model
     * @static
     *
     * @example
     *  Model().remove();
     *
     * @returns void
     */
    Remove = function () {
        if (!_.isUndefined(_.singleton.model[ReturnObj.namespace])) {
            _.singleton.model[ReturnObj.namespace].stopListening();
            delete _.singleton.model[ReturnObj.namespace];
        }
    };

    /**
     * returns an object from Instance()/singleton object
     *
     * @module Init
     * @requires Backbone.Model
     * @static
     *
     * @example
     *  Model().init({id : 123});
     *  Model().init({attributes : {model : model.auth}});
     *
     * @param {Object} settings Not required - JSON string with settings for attributes and model id
     * @returns {Object}
     */
    Init = function (settings) {
        settings = settings || {};
        var attributes = settings.attributes || {},
            init = settings.init || false;

        attributes.id = settings.id || 1;
        ReturnObj.namespace = ReturnObj.namespace + attributes.id;

        if (_.isUndefined(_.singleton.model[ReturnObj.namespace])) {
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
     * Creates a new instance and store them into model singleton object
     *
     * @module GetInstanze
     * @submodule Init
     * @static
     *
     * @param {Object} attributes Not required - JSON string with settings for attributes and model id
     */
    GetInstanze = function (attributes) {
        _.singleton.model[ReturnObj.namespace] = new ReturnObj.code(attributes);
    };

    /**
     * returns an instance from global singleton storage
     *
     * @module Model
     * @submodule getInstance
     * @extends Backbone.Model
     * @requires Backbone.Model
     * @static
     *
     * @example
     *  Model().getInstance();
     *
     * @returns {Object}
     */
    Instance = function () {
        return _.singleton.model[ReturnObj.namespace];
    };

    /**
     * The ReturnObj class stores functionality to extend a AMD module,
     * that handles initialization and removing objects of a Backbone model.
     * Namespace and code must be set by the AMD module.
     *
     * @class ReturnObj
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