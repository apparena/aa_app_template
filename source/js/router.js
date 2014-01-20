define([
    'jquery',
    'underscore',
    'backbone'
], function ($, _, Backbone) {

    var AppRouter,
        initialize;

    AppRouter = Backbone.Router.extend({
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

        initialize: function () {
            _.bindAll(this, 'setEnv', 'homeAction', 'callAction', 'moduleAction', 'loadModule', 'goToPreviewsAction', 'goToPreviewsPage');
        },

        loadModule: function (id) {
            var newModules = [],
                module = [
                    this.appModulePath,
                    this.modulePath
                ];

            // add mobile to module name and try to load a mobile version first
            if (_.aa.env.device.type === 'mobile') {
                _.each(module, function (value) {
                    newModules.push(value + '-mobile');
                    newModules.push(value);
                });

                module = newModules;
            }

            // unset maybe existing declarations and set a new config path
            require.undef('CurrentModule');
            requirejs.config({
                paths: {
                    CurrentModule: module
                }
            });

            require(['CurrentModule'], function (Module) {
                if (id !== false) {
                    Module(id);
                } else {
                    Module();
                }
            }, function (err) {
                var failedModule = err.requireModules && err.requireModules[0];
                _.debug.error('canot loadmodule: ', failedModule);
            });
        },

        moduleAction: function (module, filename, id) {
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
            if (_.isEmpty(this.currentPage) === false) {
                this.lastPage = this.currentPage;
            }

            this.currentPage = module;
            this.modulePath = '../modules/' + module + '/js/' + filename;
            this.appModulePath = '../modules/aa_app_mod_' + module + '/js/' + filename;

            this.setEnv(env);
            this.loadModule(id);
        },

        callAction: function (module) {
            // handle last and current action
            if (_.isEmpty(this.currentAction) === false) {
                this.lastAction = this.currentAction;
            }

            this.currentAction = module;

            if (_.isUndefined(this.lastEnvClass) || this.lastEnvClass === '') {
                this.navigate('', {trigger: true, replace: true});
            } else {
                this.setEnv(module);
            }
        },

        homeAction: function () {
            this.setEnv('home');
            this.modulePath = 'home';
            this.appModulePath = 'home';
            this.loadModule();
        },

        // added a new class to body from current route and removed the last one
        setEnv:     function (envClass) {
            var body = $('body');

            if (typeof this.lastEnvClass !== 'undefined') {
                body.removeClass(this.lastEnvClass);
            }
            body.addClass(envClass);
            this.lastEnvClass = envClass;
        },

        goToPreviewsAction: function () {
            this.redirection('call', this.lastAction);
        },

        goToPreviewsPage: function (trigger) {
            if (_.isEmpty(trigger)) {
                trigger = true;
            }
            this.redirection('page', this.lastPage, trigger);
        },

        redirection: function (type, page, trigger) {
            var redirect = type + '/' + page;
            if (_.isEmpty(page)) {
                redirect = '';
            }
            if (_.isEmpty(trigger)) {
                trigger = true;
            }
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
            var returnData = {type: 'notReturned', data: {}};

            if (typeof async === 'undefined') {
                async = false;
            }

            // add instance id
            data.i_id = _.aa.instance.i_id;

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
            if (_.isUndefined(type) === true || _.isUndefined(scope) === true || (type !== 'group' && _.isUndefined(data) === true)) {
                _.debug.error('Log params are not complete or wrong! Third param must be an object.', type, scope, data);
                return false;
            }

            if (type === 'group') {
                data = scope;
            }

            require(['modules/aa_app_mod_logging/js/views/LoggerView'], function (Logger) {
                var log = Logger().init();

                switch (type) {
                    case 'action':
                        log.action(scope, data);
                        break;

                    case 'admin':
                        log.admin(scope, data);
                        break;

                    case 'group':
                        log.group(data);
                        break;
                }
            });

            return this;
        };

        Backbone.View.prototype.destroy = function () {
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