let mix = require('laravel-mix')

mix.js('resources/assets/js/app.js', 'public/js')
  .copy('resources/assets/css/payroll-print.css', 'public/css')
  .copy('resources/assets/img', 'public/img', false)
  .styles([
    'resources/assets/css/app.css'
  ], 'public/css/app.css');