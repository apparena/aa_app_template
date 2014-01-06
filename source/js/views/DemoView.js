define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/demo_home.html'
], function ($, _, Backbone, HomeTemplate) {
    'use strict';

    var namespace = 'demo',
        View, Init, Remove, Instance;

    View = Backbone.View.extend({
        el: $('.content-wrapper'),

        events: {},

        initialize: function () {
            _.bindAll(this, 'render');// fixes loss of context for 'this' within methods, every function that uses 'this' as the current object should be in here
        },

        render: function () {
            var compiledTemplate = _.template(HomeTemplate, {});
            this.$el.html(compiledTemplate);
        }
    });

    Remove = function () {
        _.singleton.view[namespace].unbind().remove();
        delete _.singleton.view[namespace];
    };

    Init = function (init) {

        if (_.isUndefined(_.singleton.view[namespace])) {
            _.singleton.view[namespace] = new View();
        } else {
            if (!_.isUndefined(init) && init === true) {
                Remove();
                _.singleton.view[namespace] = new View();
            }
        }

        return _.singleton.view[namespace];
    };

    Instance = function () {
        return _.singleton.view[namespace];
    };

    return {
        init:        Init,
        view:        View,
        remove:      Remove,
        namespace:   namespace,
        getInstance: Instance
    };
});