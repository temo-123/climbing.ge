// const { vue } = require('laravel-mix');
const mix = require('laravel-mix');
require('laravel-vue-lang/mix');

require('dotenv').config({path:'.env'}); // laravel-mix for using .env varibles in VUE Components

mix.js('resources/js/app.js', 'public/assets/js').vue();
mix.sass('resources/sass/app.scss', 'public/assets/css', []);

mix.lang();

mix.webpackConfig({
    // node: {
    //   fs: "empty"
    // },
    resolve: {
    //     alias: {
    //         "handlebars" : "handlebars/dist/handlebars.js"
    //     }
        fallback: {
            "fs": false,
            "path": false,
            "os": false,
            "net": false,
            "tls": false,
            "dns": false,
            "readline": false,
            "module": false,
            "vm": false,
            "child_process": false,
            "zlib": false,
            "http": false,
            "https": false,
            "constants": false,
            "worker_threads": false,

            "crypto": require.resolve("crypto-browserify"),
            "stream": require.resolve("stream-browserify"),
        },
    },
});

mix.extend(
    "graphql",
    new (class {
        dependencies() {
            return ["graphql", "graphql-tag"];
        }

        webpackRules() {
            return {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                loader: "graphql-tag/loader"
            };
        }
    })()
);

mix.graphql();
// mix.webpackConfig({
//     stats: {
//     children: true
//     }
// });

if (mix.inProduction()) {
    mix.version();
}
