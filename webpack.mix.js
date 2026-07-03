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

    // Lazy-loaded chunks (dynamic imports) default to a bare `[name].js`
    // filename with no hash, so the browser's immutable 1-year cache
    // (see public/.htaccess) never sees a new URL when a chunk's content
    // changes on rebuild. Append a contenthash so stale chunks get busted.
    const originalChunkFilename = webpackConfig.output.chunkFilename;
    webpackConfig.output.chunkFilename = (pathData) => {
        const template =
            typeof originalChunkFilename === 'function'
                ? originalChunkFilename(pathData)
                : originalChunkFilename;

        return template.replace(/\.js$/, '.[contenthash:8].js');
    };

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

mix.version();

if (!mix.inProduction()) {
    mix.sourceMaps();
}

