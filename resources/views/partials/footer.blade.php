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
            width: 33%;
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

<body onload="substitutePdfVariables()" style="border:0;" class="text-xs">
    <div class="parent" style="padding-top: {{ isset($footer_margin) ? $footer_margin : 0 }}; border:0;">
        <div class="child" align="left" style="border:0;">
        @if (env("APP_ENV") != "production")
            VERSIÓN DE PRUEBAS
        @else
            @if (isset($print_message))
                @if ($print_message)
                    {{ $print_message }}
                @endif
            @endif
            @if (isset($print_date))
                @if ($print_date)
                    Impreso el
                    @if (isset($date))
                        {{ $date }}
                    @else
                        {{ Carbon::now()->ISOFormat('LLL') }}
                    @endif
                @endif
            @endif
        @endif
        </div>
        <div class="child" align="center" style="border:0;">
        @if (isset($paginator))
            @if ($paginator)
                <span size="3">
                    Página <span class="page"></span> de <span class="topage"></span>
                </span>
            @endif
        @endif
        </div>
        <div class="child" align="right" style="border:0;">
            <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG(bcrypt($date . ' ' . gethostname() . ' ' . env('APP_URL')), 'PDF417') }}" alt="BARCODE!!!" style="height: 20px; width: 125px;" />
        </div>
    </div>
</div>
</body>
</html>