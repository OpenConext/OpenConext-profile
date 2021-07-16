var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')

    .copyFiles({
        from: './assets/static/images/',
        to: 'images/[path][name].[hash:8].[ext]',
    })

    .addEntry('app', './assets/js/app.js')
    .addEntry('my-connections', './assets/js/my-connections.js')

    .disableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

;

module.exports = Encore.getWebpackConfig();
