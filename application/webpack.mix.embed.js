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
const tailwindcss = require('tailwindcss');
const mix = require('laravel-mix');
require('laravel-mix-purgecss');

mix.js('resources/js/embed/app.js', 'public/js/embed');
mix.sass('resources/sass/embed/app.scss', 'public/css/embed').options({
    processCssUrls: false,
    postCss: [ tailwindcss('resources/sass/embed/tailwind.config.js') ],
  });
mix.copy('node_modules/slick-carousel/slick/fonts', 'public/fonts/vendor/slick-carousel/slick/');
mix.version();
mix.sourceMaps();

mix.purgeCss();
