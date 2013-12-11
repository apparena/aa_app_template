/*global define: false, require: true */
define([
    'jquery',
    'underscore',
    'backbone',
    'logger'
], function ($, _, Backbone, Logger) {

    var AppRouter,
        initialize;

    AppRouter = Backbone.Router.extend({
        // we'll define routes in a moment
        routes: {
            '':                           'homeAction',
            'page/:module':               'moduleAction',
            'page/:module/:filename':     'moduleAction',
            'page/:module/:filename/*id': 'moduleAction',
            'call/*module':               'callAction'
        },

        currentAction: '',
        lastAction:    '',
        currentPage:   '',
        lastPage:      '',

        // set up default routes
        initialize:    function () {
            _.bindAll(this, 'setEnv', 'homeAction', 'callAction', 'moduleAction', 'loadModule', 'goToPreviewsAction', 'goToPreviewsPage');
        },

        loadModule: function (module, id) {
            //_.debug.log('load: ', module, 'id: ', id);
            require([module], function (module) {
                if (id !== false) {
                    module(id);
                } else {
                    module();
                }
            }, function (err) {
                //The errback, error callback
                //The error has a list of modules that failed
                var failedModule = err.requireModules && err.requireModules[0];
                //_.debug.log('canot loadmodule: ', failedModule);
            });
        },

        moduleAction: function (module, filename, id) {
            //_.debug.log('load module', module, filename);
            var env = module;

            if (_.isUndefined(filename)) {
                filename = 'main';
            }
            env += '-' + filename;

            if (_.isUndefined(id)) {
                id = false;
            } else {
                env += '-' + id;
            }

            // handle last and current page
            if (_(this.currentPage).isBlank() === false) {
                //console.log('set last page', this.currentPage);
                this.lastPage = this.currentPage;
            }
            //console.log('set current page', module, 'last:' + this.lastPage);
            this.currentPage = module;

            this.setEnv(env);
            this.loadModule('modulesSrc/' + module + '/js/' + filename, id);
        },

        callAction: function (module) {
            //_.debug.log('call action', module);

            // handle last and current action
            if (_(this.currentAction).isBlank() === false) {
                //console.log('set last action', this.currentAction);
                this.lastAction = this.currentAction;
            }
            //console.log('set current action', module, 'last:' + this.lastAction);
            this.currentAction = module;

            if (_.isUndefined(this.lastEnvClass) || this.lastEnvClass === '') {
                this.navigate('', {trigger: true, replace: true});
            } else {
                this.setEnv(module);
            }
        },

        homeAction: function () {
            //_.debug.log('home action');
            //_.debug.log(_.aa.env.device.type);
            //var module = '../modules/home/js/main';

            // detect mobile version and load mobile home
            var module = 'home';
            if (_.aa.env.device.type === 'mobile') {
                module = 'home-mobile';
            }
            _.current_door_id = null;
            this.setEnv(module);
            this.loadModule(module, false);
        },

        setEnv: function (envClass) {
            //_.debug.log('envClass', envClass);

            var body = $('body');

            // removed redirection body classes (Workaround for commerce bank)
            if (body.hasClass('redirectionclass')) {
                body
                    .removeClass('redirectionclass')
                    .removeClass('app-static-1')
                    .removeClass('app-static-2')
                    .removeClass('app-static-3')
                    .removeClass('app-terms')
                    .removeClass('app-privacy')
                    .removeClass('app-imprint')
                    .removeClass('greetingcards-main')
                ;
            }

            if (typeof this.lastEnvClass !== 'undefined') {
                body.removeClass(this.lastEnvClass);
            }
            body.addClass(envClass);
            this.lastEnvClass = envClass;
        },

        goToPreviewsAction: function () {
            //_.debug.log('goToPreviewsAction');
            this.redirection('call', this.lastAction);
        },

        goToPreviewsPage: function (trigger) {
            //_.debug.log('goToPreviewsPage');

            if (_(trigger).isBlank()) {
                trigger = true;
            }

            this.redirection('page', this.lastPage, trigger);
        },

        redirection: function (type, page, trigger) {
            var redirect = type + '/' + page;
            if (_(page).isBlank()) {
                redirect = '';
            }
            if (_(trigger).isBlank()) {
                trigger = true;
            }
            //_.debug.log('router redirection', type, page, redirect);
            _.router.navigate(redirect, {trigger: trigger});
        }

    });

    initialize = function () {
        var app_router = new AppRouter();

        // Extend the View class to include a navigation method goTo
        Backbone.View.prototype.goTo = function (loc, trigger) {
            if (typeof trigger === 'undefined') {
                trigger = true;
            }

            app_router.navigate(loc, {trigger: trigger});
        };

        // Extend the View class to make global ajax requests with jquery
        Backbone.View.prototype.ajax = function (data, async, callback) {
            //_.debug.log('ajax', data);
            var returnData = {type: 'notReturned', data: {}};

            if (typeof async === 'undefined') {
                async = false;
            }

            // add instance id
            data.aa_inst_id = _.aa.instance.aa_inst_id;

            $.ajax({
                url:      'ajax.php',
                dataType: 'json',
                type:     'POST',
                async:    async,
                data:     data,
                success:  function (response) {
                    returnData.type = 'success';
                    returnData.data = response;

                    if (typeof callback === 'function') {
                        callback(returnData);
                    }
                },
                error:    function (response) {
                    returnData.type = 'error';
                    returnData.data = response;
                }
            });

            return returnData;
        };

        /**
         * global function to log action with logging module
         * depend: module logging
         *
         * @param type  string log tyoe, use admin|action|agent
         * @param scope string log scope, defined by your own ex. [app|user]_[modulename]_[action]
         * @param data json params as json to save
         * @returns {*}
         */
        Backbone.View.prototype.log = function (type, scope, data) {
            //_.debug.log('log', type, scope, data);

            if (_.isUndefined(type) === true || _.isUndefined(scope) === true || _.isUndefined(data) === true) {
                //_.debug.log('Log params are not complete or wrong! Thirt param must be an object.', type, scope, data);
                return false;
            }

            if (_.isEmpty(data.data_obj)) {
                data.data_obj = {
                    empty: true
                };
            }

            //var log = new Logger();
            if (_.isUndefined(_.singleton.view.logger)) {
                _.singleton.view.logger = new Logger();
            }
            var log = _.singleton.view.logger;

            switch (type) {
                case 'action':
                    data.scope = scope;
                    log.action(data);
                    break;

                case 'admin':
                    data.scope = scope;
                    log.admin(data);
                    break;

                case 'agent':
                    log.agent();
                    break;
            }

            return this;
        };

        Backbone.View.prototype.destroy = function () {
            //_.debug.log('destroy view');
            //COMPLETELY UNBIND THE VIEW
            this.undelegateEvents();

            $(this.el).removeData().unbind();

            //Remove view from DOM
            //this.remove();
            //this.$el.empty();
        };

        Backbone.history.start();
        return app_router;
    };

    return {
        initialize: initialize
    };
});