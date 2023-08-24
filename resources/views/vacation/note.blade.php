<?php
use Carbon\Carbon;

$contract = $departure->employee->contract_in_date($departure->departure);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL</title>
    <link rel="stylesheet" href='{{ public_path('/css/report-print.min.css') }}' media="all" />
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
                        <img src='{{ public_path('/img/logo.png') }}' class="w-30">
                    </div>
                </th>
                <th class="w-50 text-right no-paffing no-margins">
                    <div class="text-right">
                        <img src='{{ public_path('/img/escudo_bolivia.gif') }}' class="w-15">
                    </div>
                </th>
            </tr>
        </thead>
    </table>
    <hr width="100%"><!-- línea -->
    <!-- E N C A B E Z A D O   N O T A -->
    <div class="text-right">
        <div>
            {{ ucwords(mb_strtolower($contract->position->position_group->company_address->city->name)) }},
            {{ Carbon::parse($departure->created_at)->ISOFormat('LL') }}
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
            CNL. MSc. CAD. LUCIO ENRIQUE RENÉ JIMÉNEZ VARGAS
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
            REF.: SOLICITUD DE PERMISO POR VACACIONES
        </div>
    </div>

    <div class="text-left">
        <div class="py-15">
            De mi mayor consideración:
        </div>
        <div class="text-justify">
            <span>
                Mediante la presente, tengo a bien dirigirme a su Autoridad con el propósito de solicitar se me autorice
                tomar vacaciones, toda vez que he coordinado con mi Jefe Inmediato Superior y Superior Jerárquico, no
                comprometiendo el desarrollo de las actividades laborales en mi área correspondiente, habiendo
                considerado
                las previsiones para el efecto, de acuerdo al siguiente detalle:
            </span>
        </div>
    </div>
    <div class="text-center">VACACIÓN</div>
    <table class="table-info w-50 m-b-5 text-center uppercase" style="float: left; margin-left: 1px;">
        <tr class="bg-grey-darker text-xs text-white">
            <td>Nombres y Apellidos</td>
        </tr>
        <tr>
            @php($name = $departure->employee->fullName())
            <td class="{{ Util::string_class_length($name, false) }} data-row py-5 text-sm">{{ $name }}</td>
        </tr>
    </table>
    <table class="table-info w-49 m-b-5 text-center uppercase" style="float: right; margin-left: 1px;">
        <tr class="bg-grey-darker text-xs text-white">
            <td class="w-50">Desde</td>
            <td class="w-50">Hasta</td>
        </tr>
        <tr class="text-sm">
            <td class="w-50 py-5">
                <span>{{ Carbon::parse($departure->departure)->format('d/m/Y') }}</span>
                <span>&nbsp;</span>
                <span>{{ Carbon::parse($departure->departure)->format('H:i') }}</span>
            </td>
            <td class="w-50 py-5">
                <span>{{ Carbon::parse($departure->return)->format('d/m/Y') }}</span>
                <span>&nbsp;</span>
                <span>{{ Carbon::parse($departure->return)->format('H:i') }}</span>
            </td>
        </tr>
    </table>
    <table class="table-info w-100 m-b-10 uppercase text-center">
        <tr class="bg-grey-darker text-xs text-white">
            <td>Cargo</td>
        </tr>
        <tr>
            <td class="{{ Util::string_class_length($contract->position->name, false) }} data-row py-5">
                {{ $contract->position->name }}</td>
        </tr>
        <tr class="bg-grey-darker text-xs text-white">
            <td>Área</td>
        </tr>
        <tr>
            <td
                class="{{ Util::string_class_length($contract->position->position_group->name, false) }} data-row py-5">
                {{ $contract->position->position_group->name }}</td>
        </tr>
    </table>
    <table class="table-info w-100 m-b-10 uppercase">
        <thead>
            <tr class="bg-grey-darker text-xs text-white text-center">
                <td>Detalle</td>
            </tr>
        </thead>
        <tbody>
            <tr class="{{ Util::string_class_length($departure->description, false) }} text-left"
                style="height: 80px; max-height: 80px;">
                <td class="text-xs px-15">{{ $departure->description }}</td>
            </tr>
        </tbody>
    </table>
    <br><br><br>
    <table class="w-100">
        <thead>
            <tr>
                <td class="w-50 text-center">
                    <hr width="300">Jefe Inmediato Superior
                </td>
                <td class="w-50 text-center">
                    <hr width="300">Superior Jerárquico
                </td>
            </tr>
            <tr>
                <td colspan="2" class="w-100 text-center">
                    <br><br>
                    <hr width="300">Solicitante
                </td>
            </tr>
        </thead>
    </table>
</body>

</html>
