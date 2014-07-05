define([
    'views/DemoView'
], function (DemoView) {
    'use strict';

    return function () {
        DemoView().init().render();
    };
});