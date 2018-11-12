
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aviso de afiliacion y reingreso del trabajador</title>
    <style>
        html,
        body {
            
        }
        .doc{
            font: 14pt "Times New Roman", "Times";
            text-align: justify;
        }
        .head{
            font-weight: bold;
            text-decoration: underline;            
        }
        .title-text{
            font-weight: bold;
        }
        .up{
            text-transform: uppercase;
        }
        .cap{
            text-transform: capitalize;
        }
        .center{
            text-align: center;
        }
        .firma{
            width: 900px;
            height: 150px;
            display: table-cell;
            vertical-align: bottom;
        }
    </style>
     <style>
        <?php include public_path('css/payroll-print.css') ?>
    </style>
</head>
<body>
<br>
<div class="doc">
    <div class="head center"> Reporte de Salidas y Licencias  </div>
    <br>
    <div style="font-size: 10pt;font-weight: bold;">
        <p>Dirección/Oficina: <span> {{ (!isset($search->position_group))?'TODOS':$search->position_group->name }} </span></p>
        <p>Empleado: <span> {{ (!isset($search->employee))?'TODOS':($search->employee->first_name.' '.$search->employee->last_name.' '.$search->employee->mothers_last_name) }} </span></p>
        <p>Estado: <span> {{ ($search->state == null)? 'TODOS':(($search->state == true)?'APROBADO':'RECHAZADO') }} </span></p> 
        <p>Del <span> {{ $search->start_date }} </span> hasta <span> {{ $search->end_date }} </span></p> 
    </div>
    <div class="content">
        <table style="font-size: 8pt;">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th width="10%">Dirección/Unidad</th>
                    <th width="10%">Nombre</th>
                    <th width="10%">Cargo</th>
                    <th width="5%">Tipo</th>
                    <th width="5%">Razon</th>
                    <th width="5%">Fecha Salida</th>
                    <th width="5%">Fecha Retorno</th>
                    <th width="5%">Hora Salida</th>
                    <th width="5%">Hora Retorno</th>
                    <th width="5%">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departures as $key => $departure)
                <tr>
                    <td> {{ ++$key }} </td>
                    <td> {{ $departure->contract->position->position_group->name }} </td>
                    <td> {{ Util::fullName($departure->contract->employee) }} </td>
                    <td> {{ $departure->contract->position->name }} </td>
                    <td> {{ $departure->departure_reason->departure_type->name }} </td>
                    <td> {{ $departure->departure_reason->name }} </td>
                    <td> {{ Carbon::parse($departure->departure_date)->format('d/m/Y') }} </td>
                    <td> {{ Carbon::parse($departure->return_date)->format('d/m/Y') }} </td>
                    <td> {{ Carbon::parse($departure->departure_time)->format('H:i') }} </td>
                    <td> {{ Carbon::parse($departure->return_time)->format('H:i') }} </td>
                    <td> {{ ($departure->approved == true)? 'APROBADO': (($departure->approved == false)? 'RECHAZADO':'PENDIENTE') }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>    
</body>
</html>
