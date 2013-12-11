define([
    'jquery',
    'underscore',
    'backbone',
    'router'
], function ($, _, Backbone, Router) {

    'use strict';

    return {
        initialize: function () {
            this.router = Router.initialize();
        }
    };
});