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
            <thead>
                <tr>
                    <th rowspan="2" width="15%">Nombre y Apellidos</th>
                    <th rowspan="2" width="10%">N° Carnet de Identidad</th>
                    <th rowspan="2" width="10%">Fecha de Ingreso</th>
                    <th rowspan="2" width="10%">N° de CAS</th>
                    <th rowspan="2" width="10%">Fecha de emisión de CAS</th>
                    <th width="15%" colspan="3">Años de Calificados</th>
                    <th rowspan="2" width="10%">N° de días asignados según CAS</th>
                    <th rowspan="2" width="10%">N° de días de vacación usadas</th>
                    <th rowspan="2" width="10%">Saldo</th>
                </tr>
                <tr>
                    <th>Años</th>
                    <th>Meses</th>
                    <th>Días</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="name">Amilkar Barda Ponce</td>
                    <td>1254896</td>
                    <td>02/01/2023</td>
                    <td>ER-55556</td>
                    <td>20/08/2020</td>
                    <td>10</td>
                    <td>04</td>
                    <td>23</td>
                    <td>30</td>
                    <td>24</td>
                    <td>6</td>
                </tr>
                <tr>
                    <td class="name">Leonel Vargas Ramírez</td>
                    <td>1254896</td>
                    <td>02/01/2023</td>
                    <td>ER-55556</td>
                    <td>20/08/2020</td>
                    <td>10</td>
                    <td>04</td>
                    <td>23</td>
                    <td>30</td>
                    <td>24</td>
                    <td>6</td>
                </tr>
                <tr>
                    <td class="name">Ximena Hurtado Carrasco</td>
                    <td>1254896</td>
                    <td>02/01/2023</td>
                    <td>ER-55556</td>
                    <td>20/08/2020</td>
                    <td>10</td>
                    <td>04</td>
                    <td>23</td>
                    <td>30</td>
                    <td>24</td>
                    <td>6</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>