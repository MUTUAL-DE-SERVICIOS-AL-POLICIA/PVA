<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="{{ mix('/css/roboto-fontface.css') }}" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div id="app">
      <app-main/>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
  </body>
</html>