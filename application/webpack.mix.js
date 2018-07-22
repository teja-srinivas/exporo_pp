let mix = require('laravel-mix');
require('laravel-mix-purgecss');

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

mix.webpackConfig({
  resolve: {
    alias: {
      // Use slim build to exclude the ajax stuff (we use axios)
      jquery$: 'jquery/dist/jquery.slim.js',
    },
  },
});

mix.js('resources/assets/js/app.js', 'public/js')
mix.sass('resources/assets/sass/app.scss', 'public/css')
mix.version();

mix.purgeCss({
  // Dropdowns and animated JS content
  whitelist: ['show', 'fade', 'collapse', 'collapsing'],

  // We use badges for user role colors
  whitelistPatterns: [/^badge-/],
});

mix.extract([
  'popper.js',
  'bootstrap/js/src/dropdown',
  'bootstrap/js/src/util',
  'jquery',
]);

// Allow custom bootstrap builds by using their source files
// This enables the babel-loader to properly compile them
Mix.listen('configReady', ({module}) => {
  module.rules.find(rule => {
    if (rule.test.test && rule.test.test('test.js')) {
      delete rule.exclude;
      rule.include = [
        __dirname + '/resources/assets/js/',
        __dirname + '/node_modules/bootstrap/js/',
      ];
    }
  });
});
