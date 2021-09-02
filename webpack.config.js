const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .copyFiles({
        from: './app/web/images/',
        to: 'images/[path][name].[hash:8].[ext]',
    })
    .enableSassLoader()
    .enablePostCssLoader()
    .addEntry('app', './app/web/js/app.js')
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();
