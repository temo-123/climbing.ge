const mix = require('laravel-mix');
const webpack = require('webpack');
require('dotenv').config({path:'.env'}); 

mix.js('resources/js/app.js', 'public/assets/js').vue({ 
  version: 3,
  options: {
    compilerOptions: {
      //
    }
  }
});

mix.sass('resources/sass/app.scss', 'public/assets/css');

mix.override((webpackConfig) => {
    webpackConfig.plugins = webpackConfig.plugins.filter((plugin) => !(plugin instanceof webpack.ProgressPlugin));

    const patchSassLoader = (rules) => {
        rules.forEach((rule) => {
            if (rule.oneOf) patchSassLoader(rule.oneOf);
            if (Array.isArray(rule.use)) {
                rule.use.forEach((u) => {
                    if (u.loader && u.loader.includes('sass-loader')) {
                        u.options = { ...u.options, api: 'modern' };
                    }
                });
            }
        });
    };
    patchSassLoader(webpackConfig.module.rules);
});

mix.webpackConfig({
    resolve: {
        extensions: ['.*', '.wasm', '.mjs', '.js', '.jsx', '.json', '.vue'],
        alias: {}
    },
    resolveLoader: {
        alias: {
            'vue-loader': 'vue-loader/dist/index.js'
        }
    },

    devtool: 'eval-source-map',
    stats: 'verbose',

    plugins: [
        new webpack.DefinePlugin({
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
        }),
    ],
});

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}

