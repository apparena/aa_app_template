define([
    'underscore'
], function (_) {

    'use strict';

    var Init, Remove, Instance, ReturnObj, GetInstanze;

    /**
     * destroy view objects and eventlistener, removes view html from DOM
     *
     * @module Remove
     * @requires Backbone.View
     * @static
     *
     * @example
     *  View().remove();
     *
     * @returns void
     */
    Remove = function () {
        if (!_.isUndefined(_.singleton.view[ReturnObj.namespace])) {
            _.singleton.view[ReturnObj.namespace].stopListening().undelegateEvents().remove();
            delete _.singleton.view[ReturnObj.namespace];
        }
    };

    /**
     * returns an object from Instance()/singleton object
     *
     * @module Init
     * @requires Backbone.View
     * @static
     *
     * @example
     *  View().init({id : 123});
     *  View().init({attributes : {model : model.auth}});
     *
     * @param settings {Object} Not required - JSON string with settings for attributes and view id
     * @returns {Object}
     */
    Init = function (settings) {
        settings = settings || {};

        var init = settings.init || false;

        if (_.isUndefined(_.singleton.view[ReturnObj.namespace])) {
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
     * Creates a new instance and store them into view singleton object
     *
     * @module GetInstanze
     * @submodule Init
     * @static
     *
     * @param settings {Object} Not required - JSON string with settings for attributes and view id
     */
    GetInstanze = function (settings) {
        settings = settings || {};

        var attributes = settings.attributes || {};
        attributes.id = settings.id || 1;

        _.singleton.view[ReturnObj.namespace] = new ReturnObj.code(attributes);
    };

    /**
     * returns an instance from global singleton storage
     *
     * @module View
     * @submodule getInstance
     * @extends Backbone.View
     * @requires Backbone.View
     * @static
     *
     * @example
     *  View().getInstance();
     *
     * @returns {Object}
     */
    Instance = function () {
        return _.singleton.view[ReturnObj.namespace];
    };

    /**
     * The ReturnObj class stores functionality to extend a AMD module,
     * that handles initialization and removing objects of a Backbone view.
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