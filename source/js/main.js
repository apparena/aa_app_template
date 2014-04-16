(function () {
    'use strict';

    require.config({
        baseUrl: '../../js/'
    });

    require([
        'jquery',
        'underscore',
        'aa_helper',
        'debug',
        'router',
        'models/AaInstanceModel',
        'views/BasicLogView',
        // libs with no object declaration
        'bootstrap'
    ], function ($, _, AaHelper, Debug, Router, AaInstanceModel, BasicLogView) {
        var admin = $('.nav-admin'),
            nav, aaInstanceModel, QueryString;

        // extend underscore with our aa object, so that it is accessible everywhere where the _ underscore object is known.
        _.extend(_, {
            sprintf:   AaHelper.sprintf,  // aa helper sprintf like in php but inly for %s
            c:         AaHelper.__c,      // aa helper like in PHP
            t:         AaHelper.__t,      // aa helper like in PHP
            debug:     Debug,             // browser safty console.log version
            uid:       0,                 // user id
            gid:       0,                 // group ID
            fangate:   null,              // show fangate only, if this is null
            singleton: {                  // storage for initialized backbone objects to init them only one time and destroy them later easier
                view:       {},
                model:      {},
                collection: {}
            }
        });

        QueryString = function () {
            // This function is anonymous, is executed immediately and
            // the return value is assigned to QueryString!
            var query_string = {},
                query = window.location.search.substring(1),
                vars = query.split('&'),
                i = 0,
                pair, arr;
            for (i; i < vars.length; i++) {
                pair = vars[i].split('=');
                // If first entry with this name
                if (typeof query_string[pair[0]] === 'undefined') {
                    query_string[pair[0]] = pair[1];
                    // If second entry with this name
                } else if (typeof query_string[pair[0]] === 'string') {
                    arr = [query_string[pair[0]], pair[1]];
                    query_string[pair[0]] = arr;
                    // If third or later entry with this name
                } else {
                    query_string[pair[0]].push(pair[1]);
                }
            }

            return query_string;
        }();

        aaInstanceModel = AaInstanceModel.init();
        aaInstanceModel.on('sync', function () {
            // make some other stuff global
            _.extend(_, {
                router: Router.initialize()
            });

            // log some device information
            BasicLogView().init();

            // get url params and store them in _.aa.params
            _.aa.params = QueryString;

            // check login status (autologin)
            require(['modules/aa_app_mod_auth/js/views/LoginView'], function (LoginView) {
                LoginView().init().handleNavigation();
            });
        });

        // check facebook request params
        if (!_.isUndefined(QueryString.signed_request)) {
            aaInstanceModel.addUrlParam('?signed_request=' + QueryString.signed_request);
        }
        aaInstanceModel.fetch();

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
            window.open(
                'admin/?admin_key=' + _.aa.custom.admin_key,
                '_blank'
            );
        });
    });
}());