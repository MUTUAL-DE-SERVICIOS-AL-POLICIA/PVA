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
        .tabla{
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }        
        .camp{
            display: table-cell;
            vertical-align: middle;            
        }        
    </style>

</head>

<body>
<div class="">
    <div class="tabla">
        <div style="height: 3.6cm;">&nbsp</div>
        <div>
            <div class="camp" style="width: 5.5cm;height: 1cm;"> {{ $contract->employee->last_name }} </div>
            <div class="camp" style="width: 5.4cm;height: 1cm;"> {{ $contract->employee->mothers_last_name }} </div>
            <div class="camp" style="width: 5.4cm;height: 1cm;"> {{ $contract->employee->first_name }} {{ $contract->employee->second_name }} </div>
            <div class="camp" style="width: 5.5cm;height: 1cm;"> &nbsp;</div>            
        </div>
        <div style="height: 1.3cm;">&nbsp</div>
        <div>
            <div class="camp" style="width: 1.8cm;height: 1cm;"> {{ Carbon::parse($contract->employee->birth_date)->day }} </div>
            <div class="camp" style="width: 1.8cm;height: 1cm;"> {{ Carbon::parse($contract->employee->birth_date)->month }} </div>
            <div class="camp" style="width: 1.8cm;height: 1cm;"> {{ Carbon::parse($contract->employee->birth_date)->year }} </div>
            <div class="camp" style="width: 2.6cm;height: 1cm;"> {{ $contract->employee->gender }} </div>
            <div class="camp" style="width: 3.6cm;height: 1cm;"> {{ $contract->employee->zone }} </div>
            <div class="camp" style="width: 4.4cm;height: 1cm;"> {{ $contract->employee->street }} </div>
            <div class="camp" style="width: 2cm;height: 1cm;"> {{ $contract->employee->number }} </div>
            <div class="camp" style="width: 3.5cm;height: 1cm;"> {{ $contract->employee->location }} </div>
        </div>
        <div style="height: 0.8cm;">&nbsp</div>
        <div>
            <div class="camp" style="width: 5.5cm;height: 1cm;"> {{ $contract->position->charge->base_wage }} </div>
            <div class="camp" style="width: 8cm;height: 1cm;"> {{ $contract->position->name }} </div>
            <div class="camp" style="width: 9cm;height: 1cm;"> 
                <div>&nbsp;</div> 
                <div class="camp" style="width: 2.1cm;height: 1cm;"> {{ date('d', strtotime($contract->date_start)) }} </div>
                <div class="camp" style="width: 4.5cm;height: 1cm;"> {{ Util::getMonthEs(date('m', strtotime($contract->date_start))) }} </div>
                <div class="camp" style="width: 2.1cm;height: 1cm;"> {{ date('Y', strtotime($contract->date_start)) }} </div>
            </div>
        </div>
        <div style="height: 0.6cm;">&nbsp</div>
        <div>
            <div class="camp" style="width: 13.3cm;height: 0.5cm;"> {{ $employer_number->company->name }} </div>
            <div class="camp" style="width: 8.7cm;height: 0.5cm;"> {{ $employer_number->number }} </div>
        </div>
        <div style="height: 0.cm;">&nbsp</div>
        <div>
            <div class="camp" style="width: 16cm;height: 1cm;"> La Paz, {{ Carbon::now()->day }} de {{ Carbon::now()->formatLocalized('%B') }} del {{ Carbon::now()->year }} </div>
        </div>
    </div>
</div>
</body>
</html>