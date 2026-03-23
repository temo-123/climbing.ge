// const { vue } = require('laravel-mix');
const mix = require('laravel-mix');
const webpack = require('webpack');
require('laravel-vue-lang/mix');

require('dotenv').config({path:'.env'}); // laravel-mix for using .env varibles in VUE Components

mix.js('resources/js/app.js', 'public/assets/js').vue({ version: 3 });
mix.sass('resources/sass/app.scss', 'public/assets/css', []);

mix.lang();

mix.webpackConfig({
    resolve: {
        extensions: ['.*', '.wasm', '.mjs', '.js', '.jsx', '.json', '.vue'],
    },
    resolveLoader: {
        alias: {
            'vue-loader': 'vue-loader/dist/index.js'
        }
    },
    devtool: false,

    plugins: [
        new webpack.DefinePlugin({
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
        }),
    ],
});
// mix.webpackConfig({
//     stats: {
//     children: true
//     }
// });

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}
