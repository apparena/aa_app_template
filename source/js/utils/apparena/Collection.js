define([
    'underscore'
], function (_) {

    'use strict';

    var Init, Remove, Instance, ReturnObj, GetInstanze;
    
    /**
     * destroy collection objects and eventlistener
     *
     * @module Remove
     * @requires Backbone.Collection
     * @static
     *
     * @example
     *  Collection().remove();
     *
     * @returns void
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
     * @module Init
     * @requires Backbone.Collection
     * @static
     *
     * @example
     *  Collection().init({id : 123});
     *  Collection().init({attributes : {model : model.auth}});
     *
     * @param settings {Object} Not required - JSON string with settings for attributes and Collection id
     * @returns {Object}
     */
    Init = function (settings) {
        settings = settings || {};

        var init = settings.init || false;

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

    /**
     * Creates a new instance and store them into Collection singleton object
     *
     * @module GetInstanze
     * @submodule Init
     * @static
     *
     * @param settings {Object} Not required - JSON string with settings for attributes and Collection id
     */
    GetInstanze = function (settings) {
        settings = settings || {};

        var attributes = settings.attributes || {};
        attributes.id = settings.id || 1;

        _.singleton.collection[ReturnObj.namespace] = new ReturnObj.code(attributes);
    };

    /**
     * returns an instance from global singleton storage
     *
     * @module Collection
     * @submodule getInstance
     * @extends Backbone.Collection
     * @requires Backbone.Collection
     * @static
     *
     * @example
     *  Collection().getInstance();
     *
     * @returns {Object}
     */
    Instance = function () {
        return _.singleton.collection[ReturnObj.namespace];
    };

    /**
     * The ReturnObj class stores functionality to extend a AMD module,
     * that handles initialization and removing objects of a Backbone Collection.
     * Namespace and code must be set by the AMD module.
     *
     * @class ReturnObj
     * @requires namespace {String}, code {Object}
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