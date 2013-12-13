(function () {
    'use strict';

    require.config({
        baseUrl: 'js/'
    });

    require([
        'jquery',
        'underscore',
        'aa_helper',
        'debug',
        'router',
        // libs with no object declaration
        //'underscore.string',
        'bootstrap'
    ], function ($, _, AaHelper, Debug, Router) {
        var admin = $('.nav-admin'), nav;

        // load underscore extension and implement it into underscore
        //_.str = require('underscore.string');
        //_.mixin(_.str.exports());
        // extend underscore with our aa object, so that it is accessible everywhere where the _ underscore object is known.
        _.extend(_, {
            aa:              aa,                // the $aa var in JS
            c:               AaHelper.__c,      // aa helper like in PHP
            t:               AaHelper.__t,      // aa helper like in PHP
            debug:           Debug,             // browser safty console.log version
            uid:             0,                 // user id
            uid_temp:        aa.uid_temp,       // temporary user id, maybe for logging module
            gid:             0,                 // group ID
            singleton:       {                  // storage for initialized backbone objects to init them only one time and destroy them later easier
                view:       {},
                model:      {},
                collection: {}
            }
        });

        aa = null;
        // remove json php output from DOM
        $('#tempcontainer').remove();

        // extend jquery to be able to pass form data as a json automatically
        // (calling serializeObject will pack the data from the name attributes as a js-object)
        // ToDo maybe put this into a jQuery plugin file under utils
        /*$.fn.serializeObject = function () {
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
        };*/

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
        // mobile navigation autoclose after click (bootstrap bughandler)
        $('.link-element').on('click', function () {
            if ($('.navbar-toggle').css('display') === 'block') {
                $('.navbar-collapse').collapse('hide');
            }
        });
        // navigation active class handling on brand
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

        // make router functions global
        _.extend(_, {
            router: Router.initialize()
        });

        _.t('footer_terms');
    });
}());