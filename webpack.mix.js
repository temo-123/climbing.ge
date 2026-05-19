const mix = require('laravel-mix');
const webpack = require('webpack');
require('dotenv').config({path:'.env'}); 

mix.js('resources/js/app.js', 'public/assets/js').vue({ 
  version: 3,
  options: {
    compilerOptions: {
      isCustomElement: (tag) => tag === 'behold-widget'
    }
  }
});

mix.sass('resources/sass/app.scss', 'public/assets/css');

mix.override((webpackConfig) => {
    webpackConfig.plugins = webpackConfig.plugins.filter((plugin) => !(plugin instanceof webpack.ProgressPlugin));

    // Inject Instagram env vars into whichever DefinePlugin Mix registered
    webpackConfig.plugins.forEach((plugin) => {
        if (plugin.constructor.name === 'DefinePlugin' && plugin.definitions) {
            plugin.definitions['process.env.MIX_INSTAGRAM_FEED_ID'] = JSON.stringify(process.env.MIX_INSTAGRAM_FEED_ID || '');
            plugin.definitions['process.env.MIX_INSTAGRAM_HASHTAGS'] = JSON.stringify(process.env.MIX_INSTAGRAM_HASHTAGS || '');
            plugin.definitions['process.env.MIX_GOOGLE_MAPS_API_KEY'] = JSON.stringify(process.env.MIX_GOOGLE_MAPS_API_KEY || '');
        }
    });

    const patchRules = (rules) => {
        rules.forEach((rule) => {
            if (rule.oneOf) patchRules(rule.oneOf);
            if (Array.isArray(rule.use)) {
                rule.use.forEach((u) => {
                    if (u.loader && u.loader.includes('sass-loader')) {
                        u.options = { ...u.options, api: 'modern' };
                    }
                    if (u.loader && u.loader.includes('vue-loader')) {
                        u.options = u.options || {};
                        u.options.compilerOptions = u.options.compilerOptions || {};
                        u.options.compilerOptions.isCustomElement = (tag) => tag === 'behold-widget';
                    }
                });
            }
            if (rule.loader && rule.loader.includes('vue-loader')) {
                rule.options = rule.options || {};
                rule.options.compilerOptions = rule.options.compilerOptions || {};
                rule.options.compilerOptions.isCustomElement = (tag) => tag === 'behold-widget';
            }
        });
    };
    patchRules(webpackConfig.module.rules);
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

