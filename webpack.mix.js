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

mix.sass('resources/sass/app.scss', 'public/assets/css', {
  sassOptions: {
    api: 'modern'
  }
});

mix.override((webpackConfig) => {
    webpackConfig.plugins = webpackConfig.plugins.filter((plugin) => !(plugin instanceof webpack.ProgressPlugin));
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

