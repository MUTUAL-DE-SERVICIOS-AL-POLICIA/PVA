<?php
    use \App\Helpers\Util;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Hola 2023</title>
    </head>

    <body>
        <div>
            <div class="header-left">
                <img id="header-image" src="{{ public_path().'/img/logo.png' }}">
            </div>
            <div class="header-center">
                <h2>
                    Reporte Saldos General
                </h2>
            </div>
            <div class="header-right">
                Aca estará el cuadro derecho
            </div>
        </div>
        <table align="center">
            <tbody>
                <tr>
                    <td class="header-text text-left" width="40%">Nombres y Apellidos</td>
                    <td class="text-left" width="60%">Leonel Maximo Vargas Ramírez</td>
                </tr>
                <tr>
                    <td class="header-text text-left" width="40%">Cargo</td>
                    <td class="text-left" width="60%">Auxiliar I - Automatización y testeo de software</td>
                </tr>
                <tr>
                    <td class="header-text text-left" width="40%">Unidad Organizacional</td>
                    <td class="text-left" width="60%">Unidad de Sistemas y Soporte Técnico</td>
                </tr>
                <tr>
                    <td class="header-text text-left" width="40%">Fecha de Emisión CAS</td>
                    <td class="text-left" width="60%">22/02/2023</td>
                </tr>
                <tr>
                    <td class="header-text text-left" width="40%">N° de días asignados según CAS</td>
                    <td class="text-left" width="60%">15</td>
                </tr>
            </tbody>
        </table>
        <br>
        <br>
        <table align="center">
            <thead>
                <tr>
                    <th width="4%">N°</th>
                    <th width="30%">Concepto</th>
                    <th width="16%">Fecha de solicitud</th>
                    <th width="16%">Fecha inicial</th>
                    <th width="16%">Fecha final</th>
                    <th width="9%">N° de días</th>
                    <th width="9%">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Vacaciones renumeradas</td>
                    <td>01/02/2023</td>
                    <td>12/03/2023</td>
                    <td>22/03/2023</td>
                    <td>15</td>
                    <td>5</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Vacaciones renumeradas, con pago en días de vacación</td>
                    <td>01/02/2023</td>
                    <td>12/03/2023</td>
                    <td>22/03/2023</td>
                    <td>24</td>
                    <td>17</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>