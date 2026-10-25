const mix = require('laravel-mix');
const webpack = require('webpack');
require('dotenv').config({path:'.env'}); 

mix.js('resources/js/app.js', 'public/assets/js').vue({ 
  version: 3,
  // app.js mounts the root with a render function and no component uses
  // string templates, so the template compiler (~190 KB) isn't shipped
  runtimeOnly: true,
  options: {
    compilerOptions: {
      isCustomElement: (tag) => tag === 'behold-widget'
    }
  }
});

// Bootstrap 5 is compiled from node_modules inside app.scss (no CDN copies).
// Its sources still use @import / global color functions, so silence those
// deprecation warnings instead of flooding the watch output.
mix.sass('resources/sass/app.scss', 'public/assets/css', {
    sassOptions: {
        quietDeps: true,
        silenceDeprecations: ['import', 'global-builtin', 'color-functions'],
    },
});

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

    // Mix turns webpack's default split groups off, so every lazy page chunk
    // inlined its own copy of the libraries it uses (paper.js in 11 chunks,
    // the image cropper in 41, …). Restore webpack's defaults for async chunks:
    // shared node_modules / shared app modules become one cached chunk each.
    // The initial app.js is untouched (chunks: 'async'). Own group names:
    // Mix re-merges `default`/`defaultVendors: false` after this override.
    webpackConfig.optimization = webpackConfig.optimization || {};
    webpackConfig.optimization.splitChunks = {
        chunks: 'async',
        cacheGroups: {
            asyncVendors: { test: /[\\/]node_modules[\\/]/, priority: -10, reuseExistingChunk: true },
            asyncShared: { minChunks: 2, priority: -20, reuseExistingChunk: true },
        },
    };
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

    // eval source maps only while developing: in a production build they
    // wrap every module in an eval() string with an inline map, which made
    // app.js ~11 MB (2.5 MB gzipped) instead of ~1.7 MB, with the code inside
    // those strings left unminified
    devtool: mix.inProduction() ? false : 'eval-source-map',
    stats: 'verbose',

    plugins: [
        new webpack.DefinePlugin({
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
        }),
        // moment is only used for formatting (no moment.locale() anywhere):
        // don't bundle its ~160 locale files into every chunk that imports it
        new webpack.IgnorePlugin({ resourceRegExp: /^\.\/locale$/, contextRegExp: /moment$/ }),
    ],
});

mix.version();

if (!mix.inProduction()) {
    mix.sourceMaps();
}

