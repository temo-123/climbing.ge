// const { vue } = require('laravel-mix');
const mix = require('laravel-mix');
require('laravel-vue-lang/mix');

require('dotenv').config({path:'../../.env'}); // laravel-mix for using .env varibles in VUE Components

mix.js('resources/js/app.js', 'public/assets/js').vue();
mix.sass('resources/sass/app.scss', 'public/assets/css', []);

mix.lang();

mix.webpackConfig({
    // node: {
    //   fs: "empty"
    // },
    // resolve: {
    //     alias: {
    //         "handlebars" : "handlebars/dist/handlebars.js"
    //     }
    // },
});
// mix.webpackConfig({
//     stats: {
//     children: true
//     }
// });
