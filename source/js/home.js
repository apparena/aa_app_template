define([
    'underscore',
    'views/DemoView'
], function (_, DemoView) {
    'use strict';

    return function () {
        DemoView.init().render();
    };
});