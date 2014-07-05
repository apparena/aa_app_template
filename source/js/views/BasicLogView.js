define([
    'ViewExtend',
    'jquery',
    'underscore',
    'backbone'
], function (View, $, _, Backbone) {
    'use strict';

    return function () {
        View.namespace = 'BasicLogView';

        View.code = Backbone.View.extend({
            initialize: function () {
                this.log('group', {
                    'user_device':  _.aa.env.device.type,
                    'user_browser': _.aa.env.browser.name + ' ' + _.aa.env.browser.version,
                    'user_os':      _.aa.env.browser.platform,
                    'app_page':     _.aa.env.base,
                    'app_openings': 'start'
                });
            }
        });

        return View;
    };
});