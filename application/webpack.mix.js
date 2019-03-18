const mix = require('laravel-mix');
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

      // Use runtime-only version of vue
      vue$: 'vue/dist/vue.runtime.esm.js',
    },
  },
});

mix.js('resources/js/app.js', 'public/js')
mix.sass('resources/sass/app.scss', 'public/css')
mix.version();
mix.sourceMaps();

mix.purgeCss({
  // Dropdowns and animated JS content
  whitelist: [
    'arrow', 'show', 'fade', 'collapse', 'collapsing',
    'form-group', 'form-row', 'fieldset', 'legend', 'custom-switch',
  ],

  whitelistPatterns: [
    // We use badges for user role colors
    /^badge-/,

    // Dynamic JS stuff
    /^pag/,
    /^popover/,
    /^bs-popover/,
    /^tooltip/,
    /^bs-tooltip/,
  ],
});

mix.extract([
  'axios',
  'popper.js',
  'jquery',
]);
