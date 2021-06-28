var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')

    .copyFiles({
        from: './app/web/images/',
        to: 'images/[path][name].[hash:8].[ext]',
    })
    .enableSassLoader()
    .addEntry('app', './app/web/js/app.js')
    .addEntry('my-connections', './app/web/js/my-connections.js')

    .disableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

;

module.exports = Encore.getWebpackConfig();
