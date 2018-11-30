let mix = require('laravel-mix')

mix.js('resources/assets/js/app.js', 'public/js')
  .copy('resources/assets/css/payroll-print.css', 'public/css')
  .minify('public/css/payroll-print.css')
  .copy('resources/assets/css/roboto-fontface.css', 'public/css')
  .minify('public/css/roboto-fontface.css')
  .copy('resources/assets/img', 'public/img', false)
  .copy('resources/assets/fonts', 'public/fonts', false)
  .sass('resources/assets/sass/report-print.scss', 'public/css')
  .minify('public/css/report-print.css')

if (mix.inProduction()) {
  mix.version()
}