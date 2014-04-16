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
            'app/:filename':              'appAction',
            'app/:filename/*id':          'appAction',
            'mod/:module':                'moduleAction',
            'mod/:module/:filename':      'moduleAction',
            'mod/:module/:filename/*id':  'moduleAction',
            'call/*module':               'callAction',
            // deprecated
            'page/:module':               'moduleAction',
            'page/:module/:filename':     'moduleAction',
            'page/:module/:filename/*id': 'moduleAction'
        },

        currentAction: '',
        lastAction:    '',
        currentPage:   '',
        lastPage:      '',

        /**
         * Description
         * @method initialize
         * @return
         */
        initialize: function () {
            _.bindAll(this, 'setEnv', 'homeAction', 'callAction', 'moduleAction', 'appAction', 'loadModule', 'goToPreviewsAction', 'goToPreviewsPage');
        },

        /**
         * Description
         * @method loadModule
         * @param {String|Number|Boolean} id
         * @return
         */
        loadModule: function (id) {
            var that = this,
                newModules = []/*,
             module = [
             this.appModulePath,
             this.modulePath
             ]*/;

            // add mobile to module name and try to load a mobile version first
            /*if (_.aa.env.device.type === 'mobile') {
             _.each(this.module, function (value) {
             newModules.push(value + '-mobile');
             newModules.push(value);
             });

             this.module = newModules;
             }*/

            // unset maybe existing declarations and set a new config path
            require.undef('CurrentModule');
            requirejs.config({
                paths: {
                    CurrentModule: this.module
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
                _.debug.error('canot loadmodule', that.module[0] + '.js');
            });
        },

        /**
         * Call a file in the specified module JS directory
         * @method moduleAction
         * @param {String|Boolean} module
         * @param {String} filename
         * @param {String} id
         * @return
         */
        moduleAction: function (module, filename, id) {
            filename = filename || 'main';
            module = module || false;
            id = id || false;

            //_.debug.log('moduleAction', module);
            //_.debug.log(this.lastPage, this.lastAction, this.currentPage);

            var env = module;

            if (module === false) {
                env = filename;
            } else {
                env += '-' + filename;
            }

            if (id !== false) {
                env += '-' + id;
            }

            // handle last and current page
            if (_.isEmpty(this.currentPage) === false) {
                this.lastPage = this.currentPage;
            }

            this.currentPage = module;
            // define module pathes for module calls
            this.module = [
                '../modules/aa_app_mod_' + module + '/js/' + filename,
                '../modules/' + module + '/js/' + filename
            ];

            // only for app calls
            if (module === false) {
                this.currentPage = filename;
                this.module = [
                    filename
                ];
            }
            //_.debug.log(this.lastPage, this.lastAction, this.currentPage);
            this.setEnv(env);
            this.loadModule(id);
        },

        /**
         * Call a file in the app JS directory
         * @method appAction
         * @param {String} filename
         * @param {String} id
         * @return void
         */
        appAction: function (filename, id) {
            filename = filename || 'main';
            id = id || false;

            this.moduleAction(false, filename, id);
        },

        /**
         * change only url parameters and body css classes, without calling a file/module or anything else
         * @method callAction
         * @param {String} module
         * @return
         */
        callAction: function (module) {
            //_.debug.log('callAction', module);

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

        /**
         * call index page or load team
         * @method homeAction
         * @return
         */
        homeAction: function () {
            this.setEnv('home');

            this.module = [
                'home'
            ];

            this.loadModule();
        },

        /**
         * added a new class to body from current route and removed the last one
         * @method setEnv
         * @param {String} envClass
         * @return
         */
        setEnv: function (envClass) {
            var body = $('body');

            if (typeof this.lastEnvClass !== 'undefined') {
                body.removeClass(this.lastEnvClass);
            }
            body.addClass(envClass);
            this.lastEnvClass = envClass;
        },

        /**
         * Description
         * @method goToPreviewsAction
         * @return
         */
        goToPreviewsAction: function () {
            this.redirection('call', this.lastAction);
        },

        /**
         * Description
         * @method goToPreviewsPage
         * @param {Boolean} trigger
         * @return
         */
        goToPreviewsPage: function (trigger) {
            trigger = trigger || true;
            //_.debug.log('this.lastPage', this.lastPage, this.lastAction, this.currentPage);
            // todo we need a dynamic type param as first param!
            this.redirection('mod', this.lastPage, trigger);
        },

        /**
         * Description
         * @method redirection
         * @param {String} type
         * @param {String} page
         * @param {Boolean} trigger
         * @return
         */
        redirection: function (type, page, trigger) {
            var redirect = type + '/' + page;
            if (page === '/') {
                redirect = '';
            }
            trigger = trigger || true;
            _.router.navigate(redirect, {trigger: trigger});
        }
    });

    /**
     * Description
     * @return app_router
     */
    initialize = function () {
        var app_router = new AppRouter();

        /**
         * Extend the View class to include a navigation method goTo
         * @method goTo
         * @param {String} loc
         * @param {Boolean} trigger
         * @return
         */
        Backbone.View.prototype.goTo = function (loc, trigger) {
            trigger = trigger || true;

            app_router.navigate(loc, {trigger: trigger});
        };

        /**
         * Extend the View class to make global ajax requests with jquery
         * @method ajax
         * @deprecated
         * @param {Object} data
         * @param {Boolean} async
         * @param {Function} callback
         * @return returnData
         */
        Backbone.View.prototype.ajax = function (data, async, callback) {
            var returnData = {type: 'notReturned', data: {}};

            if (typeof async === 'undefined') {
                async = false;
            }

            // add instance id
            data.i_id = _.aa.instance.i_id;

            $.ajax({
                url:      _.aa.instance.base_url + 'ajax/',
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
         * @param {String} type  string log tyoe, use admin|action|agent
         * @param {String} scope string log scope, defined by your own ex. [app|user]_[modulename]_[action]
         * @param {Object} data json params as json to save
         * @returns {Object}
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

        /**
         * Description
         * @method destroy
         * @return {Object} app_router
         */
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