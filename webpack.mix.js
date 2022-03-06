// const { vue } = require('laravel-mix');
const mix = require('laravel-mix');
require('laravel-vue-lang/mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/assets/js')
//     .sass('resources/sass/app.scss', 'public/assets/css');

// mix.js('resources/js/app.js', 'public/assets/js').vue()
//     .postCss('resources/sass/app.scss', 'public/assets/css', [ 
// ]);

require('dotenv').config({path:'../../.env'}); // laravel-mix for using .env varibles in VUE Components

mix.js('resources/js/app.js', 'public/assets/js').vue();

mix.sass('resources/sass/app.scss', 'public/assets/css', []);

mix.lang();
// mix.webpackConfig({
//     stats: {
//     children: true
//     }
// });
