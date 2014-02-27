define([
    'ModelExtend',
    'backbone',
    'underscore'
], function (Model, Backbone, _) {
    'use strict';

    Model.namespace = 'authPasswordLost';

    Model.code = Backbone.Model.extend({
        url:      '/aa_app_template/source/5091/assets/js/api/',
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
            _.bindAll(this, 'extendUnderscore');
            this.on('sync', this.extendUnderscore, this);
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