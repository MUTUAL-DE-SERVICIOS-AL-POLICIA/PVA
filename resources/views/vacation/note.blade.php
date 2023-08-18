<?php
use \Carbon\Carbon;

$contract = $departure->employee->contract_in_date($departure->departure);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>PLATAFORMA VIRTUAL - MUSERPOL</title>
    <link rel="stylesheet" href='{{ public_path("/css/report-print.min.css")}}' media="all" />
    <style>
      body {
        border: 0;
        line-height: 2;
      }
    </style>
  </head>
  <body>
    <!-- E N C A B E Z A D O   D O C U M E N T O -->
    <table class="w-100">
      <thead>
        <tr>
          <th class="w-50 text-left no-padding no-margins">
            <div class="text-left">
              <img src='{{ public_path("/img/logo.png")}}' class="w-30">
            </div>
          </th>
          <th class="w-50 text-right no-paffing no-margins">
            <div class="text-right">
              <img src='{{ public_path("/img/escudo_bolivia.gif")}}' class="w-15">
            </div>
          </th>
        </tr>
      </thead>
    </table>
    <hr width="100%"><!-- línea -->
    <!-- E N C A B E Z A D O   N O T A -->
    <div class="text-right">
      <div>
        {{ ucwords(mb_strtolower($contract->position->position_group->company_address->city->name)) }}, {{ Carbon::parse($departure->created_at)->ISOFormat('LL') }}
      </div>
      <div>
        {{ $departure->cite }}
      </div>
    </div>
    <div class="text-left">
      <div>
        Señor:
      </div>
      <div>
        CNL. MSc. CAD. LUCIO ENRIQUÉ RENÉ JIMÉNEZ VARGAS
      </div>
      <div class="font-bold">
        DIRECTOR GENERAL EJECUTIVO<br>
        MUSERPOL
      </div>
      <div class="underline font-bold">
        Presente.-
      </div>
    </div>
    <!-- C U E R P O   N O T A -->
    <div class="py-15">
      <div class="text-right font-bold uppercase">
        REF.: SOLICITUD DE VACACIONES
      </div>
    </div>
    <div class="text-left">
      <div class="text-justify">
        <span>
          Mediante la presente, tengo a bien dirigirme a su Autoridad a objeto de solicitar se me autorice la LICENCIA DE VACACIONES, amparado por
          el Reglamento Interno de Personal de la Mutual de Servicio al Policía,
        </span>
      </div>
    </div>
  </body>
</html>