/*global aa: true */
(function () {
    'use strict';

    require.config({
        baseUrl: 'js/'
    });

    require([
        'jquery',
        'underscore',
        'app',
        'aa_helper',
        'jqvalidation',
        'bootstrap',
        'underscore.string',
        'debug',
        'snow'
    ], function () {
        var $ = require('jquery'),
            _ = require('underscore'),
            App = require('app'),
            aa_helper = require('aa_helper'),
            Debug = require('debug'),
            admin = $('.nav-admin'), nav;

        // load underscore extension and implement it into underscore
        _.str = require('underscore.string');
        _.mixin(_.str.exports());
        // extend underscore with our aa object, so that it is accessible everywhere where the _ underscore object is known.
        _.extend(_, {
            aa:              aa,
            aa_helper:       aa_helper,
            debug:           Debug,
            uid:             0,
            uid_temp:        aa.uid_temp,
            gid:             0,
            current_door_id: null,
            singleton:       {
                view:       {},
                model:      {},
                collection: {}
            }
        });

        aa = null;
        $('#tempcontainer').remove();

        // extend jquery to be able to pass form data as a json automatically
        // (calling serializeObject will pack the data from the name attributes as a js-object)
        $.fn.serializeObject = function () {
            var items = {},
                form = this[ 0 ],
                index,
                item;

            if (typeof form === 'undefined') {
                return {};
            }

            for (index = 0; index < form.length; index++) {
                item = form[ index ];

                if (typeof( item.type ) !== 'undefined' && item.type === 'checkbox') {
                    item.value = $(item).is(':checked');
                }

                if (typeof( item.name ) !== 'undefined' && item.name.length > 0) {
                    items[ item.name ] = item.value;
                } else {
                    if (typeof( item.id ) !== 'undefined' && item.id.length > 0) {
                        items[ item.id ] = item.value;
                    }
                }
            }
            return items;
        };

        // @see http://jqueryvalidation.org/
        $.validator.setDefaults({
            debug:        true,
            validClass:   'has-success',
            focusCleanup: false,
            focusInvalid: true,
            errorClass:   'has-error',
            errorElement: 'span', // contain the error msg in a span tag
            ignore:       '.ignore',

            errorPlacement: function (error, element) {
                //_.debug.log('validate errorPlacement');
                // modify error object
                error.addClass('help-block');

                if (element.parent().hasClass('input-prepend') || element.parent().hasClass('input-append')) {
                    // if the input has a prepend or append element, put the validation msg after the parent div
                    error.insertAfter(element.parent());
                } else {
                    // else just place the validation message immediatly after the input
                    error.insertAfter(element);
                }
            },

            highlight: function (element, errorClass, validClass) {
                //_.debug.log('validate highlight');
                $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
            },

            unhighlight: function (element, errorClass, validClass) {
                //_.debug.log('validate unhighlight');
                $(element).closest('.form-group').removeClass(errorClass).addClass(validClass); // add the Bootstrap error class to the control group
            },

            success: function (element) {
                //_.debug.log('validate success');
                //element.closest('form').find('fieldset').prop('disabled', true).find('button').button('loading');
                element.remove();
                this.unhighlight();
            }/*,

             // this works not in chrome
             submitHandler: function (form) {
             _.debug.log('validate submit handler', form);
             $(form).find('fieldset').prop('disabled', false).find('button').button('reset');
             }*/
        });

        // show and hide debug output
        $('.show-debug').on('click', function () {
            var that = $(this),
                element = that.attr('data-content'),
                debug = $('#' + element);
            if (debug.css('display') === 'block') {
                debug.hide();
            } else {
                debug.show();
            }

            if (that.hasClass('btn-success')) {
                that.removeClass('btn-success').addClass('btn-default');
            } else {
                that.removeClass('btn-default').addClass('btn-success');
            }
        });

        // navigation active handler
        nav = $('#main-navigation').find('.navbar-collapse');
        nav.on('click', 'li', function () {
            nav.find('li').removeClass('active');
            $(this).addClass('active');
        });
        // mobile navigation autoclose after click
        $('.link-element').on('click', function () {
            if ($('.navbar-toggle').css('display') === 'block') {
                $('.navbar-collapse').collapse('hide');
            }
        });
        // navigation active class handling
        nav.closest('nav').on('click', '.navbar-brand', function () {
            nav.find('li').removeClass('active');
        });

        // add click event to admin button
        admin.on('click', function () {
            //_.debug.log('button clicked');
            window.open(
                'modules/admin_panel/index.php?aa_inst_id=' + _.aa.instance.aa_inst_id + '&admin_key=' + _.aa.custom.admin_key,
                '_blank'
            );
        });

        // generate share button in navigation
        require(['underscore', 'modulesSrc/share/js/views/GenerateShareButtonView'], function (_, GenerateShareButtonView) {
            //if (_.isUndefined(_.singleton.view.generateShareButton)) {
            _.singleton.view.generateShareButton = new GenerateShareButtonView();
            //}
            var shareBtn = _.singleton.view.generateShareButton;
            $('.navbar-right').prepend(shareBtn.render({section: 'navigation'}).getButton());
        });

        // handle facebook stuff and boot process
        require(['underscore', 'modulesSrc/facebook/js/views/FacebookView'], function (_, Facebook) {
            //facebook = new Facebook();
            if (_.isUndefined(_.singleton.view.facebook)) {
                _.singleton.view.facebook = new Facebook();
            }
            _.singleton.view.facebook.autoGrow();

            // handle facebook friend selection returns
            if (_.aa.fb.request_id > 0) {
                _.singleton.view.facebook.handleFriendReturns();
            }
        });

        // start snow effects
        if (_.aa_helper.__c('activate_snow') === '1') {
            $('body').jSnow({
                flakes:   _.aa_helper.__c('snow_flake_amount'),
                interval: 30,
                zIndex:   65535,
                //vSize:    '850',
                fadeAway: true
            });
        }

        // initialize router and some other prototype functions
        App.initialize();
        // make router functions global
        _.extend(_, {
            router: App.router
        });
        // check login status and handle navigation
        _.router.navigate('/page/participate/main/checklogin', {trigger: true});
    });
}());