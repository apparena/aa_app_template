define([
    'ModelExtend',
    'backbone',
    'underscore',
    'jquery'
], function (Model, Backbone, _, $) {
    'use strict';

    Model.namespace = 'aaInstanceModel';

    Model.code = Backbone.Model.extend({
        url:      $('body').data('url-path') + 'assets/js/api/',
        defaults: {
            config:   {},
            locale:   {},
            instance: {},
            env:      {},
            fb:       {},
            app_data: {},
            custom:   {}
        },

        initialize: function () {
            _.bindAll(this, 'extendUnderscore', 'addUrlParam');
            this.on('sync', this.extendUnderscore, this);
        },

        addUrlParam: function (param) {
            this.url = this.url + param;
        },

        extendUnderscore: function () {
            _.extend(_, {
                aa:       this.attributes,     // the $aa var in JS
                uid_temp: this.get('uid_temp') // temporary user id, maybe for logging module
            });
        }
    });

    return Model;
});