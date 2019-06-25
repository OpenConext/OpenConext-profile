var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')

    .copyFiles({
        from: './web/images/',
        to: 'images/[path][name].[hash:8].[ext]',
    })

    .addEntry('app', './web/js/app.js')
    .addEntry('my-connections', './web/js/my-connections.js')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

;

module.exports = Encore.getWebpackConfig();
