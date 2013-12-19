({
    /**
     * more infos under: https://github.com/jrburke/r.js/blob/master/build/example.build.js
     */
    mainConfigFile: '../source/configs/require-config.js',
    appDir:         '../source',
    baseUrl:        './js',
    dir:            '../dist',

    /**
     * JS and CSS optimizations
     */
    skipDirOptimize:    true,
    optimizeCss:        'standard', //set standard to optimize css. if there problems with IE set standard.keepLines
    optimize:           'uglify2',
    generateSourceMaps: false,

    /**
     * additionals
     */
    removeCombined:             false,
    preserveLicenseComments:    false,
    findNestedDependencies:     true,
    optimizeAllPluginResources: true,
    useStrict:                  true,

    modules: [
        // WARNING: Do not remove this entry, it will be required
        // by all your bundles.
        {
            name:    'main',
            include: ['require', 'text', 'main']
        },

        //
        // Add your bundles here.
        // Make sure you always exclude 'main',
        // unless you want to have
        // one single big file with every bundle
        // and dependency. This is
        // not recommended.
        //
		// EXAMPLE FOR NEW MODULE PAGE:
        // {
        //     name:    'modulesSrc/app/js/static',
        //     exclude: ['main']
        // }
    ]
})
