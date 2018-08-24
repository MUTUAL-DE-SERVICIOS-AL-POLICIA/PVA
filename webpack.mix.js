const LiveReloadPlugin = require('webpack-livereload-plugin')
let mix = require('laravel-mix')

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

mix.js('resources/assets/js/app.js', 'public/js')
	.styles([
       'resources/assets/css/vuetify.min.css',
    ], 'public/css/all.css')
  .copy('resources/assets/css/payroll-print.css', 'public/css')
  .copy('resources/assets/img', 'public/img', false)
  .webpackConfig({
    plugins: [
      new LiveReloadPlugin()
    ]
  })