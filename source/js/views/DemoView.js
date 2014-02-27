define([
    'ViewExtend',
    'jquery',
    'underscore',
    'backbone',
    'text!templates/pages/demo_home.html'
], function (View, $, _, Backbone, HomeTemplate) {
    'use strict';

    return function () {
        View.namespace = 'demo';

        View.code = Backbone.View.extend({
            el: $('.content-wrapper'),

            events: {},

            /**
             * Description
             * @method initialize
             * @return 
             */
            initialize: function () {
                _.bindAll(this, 'render');// fixes loss of context for 'this' within methods, every function that uses 'this' as the current object should be in here
            },

            /**
             * Description
             * @method render
             * @return ThisExpression
             */
            render: function () {
                var compiledTemplate = _.template(HomeTemplate, {});
                this.$el.html(compiledTemplate);
                return this;
            }
        });

        return View;
    }
});