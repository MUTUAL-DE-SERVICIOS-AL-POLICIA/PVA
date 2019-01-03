<?php
use \Carbon\Carbon;
use \Milon\Barcode\DNS2D;
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .parent {
            width: 100%;
            padding-top: 1px;
        }
        .child{
            display: inline-block;
            width: 32%;
        }
    </style>
    <script>
        function substitutePdfVariables() {

            function getParameterByName(name) {
                var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
                return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
            }

            function substitute(name) {
                var value = getParameterByName(name);
                var elements = document.getElementsByClassName(name);

                for (var i = 0; elements && i < elements.length; i++) {
                    elements[i].textContent = value;
                }
            }

            ['frompage', 'topage', 'page', 'webpage', 'section', 'subsection', 'subsubsection']
                .forEach(function(param) {
                    substitute(param);
                });
        }
    </script>
</head>

<body onload="substitutePdfVariables()">
    <div class="parent" style="padding-top: {{ $footer_margin }};">
        <div class="child" align="left">
        @if (env("APP_ENV") != "production")
            VERSIÓN DE PRUEBAS
        @else
            @if ($print_date)
                Impreso el {{ $date }}
            @endif
        @endif
        </div>
        <div class="child" align="center">
        @if ($paginator)
            <span size="3">
                Página <span class="page"></span> de <span class="topage"></span>
            </span>
        @endif
        </div>
        <div class="child" align="right">
            <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG(bcrypt($date . ' ' . gethostname() . ' ' . env('APP_URL')), 'PDF417') }}" alt="BARCODE!!!" style="height: 20px; width: 125px;" />
        </div>
    </div>
</div>
</body>
</html>