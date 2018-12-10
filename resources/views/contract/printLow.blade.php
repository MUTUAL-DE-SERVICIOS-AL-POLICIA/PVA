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
        <div style="height: 2.5cm;">&nbsp</div>
        <div align="right">
            @if (!$contract->number_insurance)
            <div class="camp" style="height: 0.5cm;"> CI: {{ $contract->employee->identity_card }} {{ $contract->employee->city_identity_card->shortened }} </div>
            @endif
        </div>
        <div style="height: 1cm;">&nbsp</div>
        <div>
            <div class="camp" style="width: 5.5cm;height: 1cm;"> {{ $contract->employee->last_name }} </div>
            <div class="camp" style="width: 5.4cm;height: 1cm;"> {{ $contract->employee->mothers_last_name }} </div>
            <div class="camp" style="width: 5.4cm;height: 1cm;"> {{ $contract->employee->first_name }} {{ $contract->employee->second_name }} </div>
            <div class="camp" style="width: 5.5cm;height: 1cm;"> {{ $contract->number_insurance }} </div>
            
        </div>
        <div style="height: 1.2cm;">&nbsp</div>
        <div>
            <div class="camp" style="width: 5.4cm;height: 1.5cm;"> {{ $contract->position->charge->base_wage }} </div>            
            <div class="camp"> 
                <div style="height: 0.7cm">&nbsp;</div> 
                <div class="camp" style="width: 2.5cm;height: 1cm;"> {{ date('d', strtotime($contract->date_end)) }} </div>
                <div class="camp" style="width: 5.9cm;height: 1cm;"> {{ Util::getMonthEs(date('m', strtotime($contract->date_end))) }} </div>
                <div class="camp" style="width: 2.5cm;height: 1cm;"> {{ date('Y', strtotime($contract->date_end)) }} </div>
            </div>
            <div class="camp" style="width: 5.4cm;height: 1.5cm;"> {{ ($contract->retirement_reason->name) ? $contract->retirement_reason->name: '-' }} </div>
        </div>
        <div style="height: 0.8cm;">&nbsp</div>
        <div>
            <div class="camp" style="width: 16.5cm;height: 1.2cm;"> {{ $employer_number->company->name }} </div>
            <div class="camp" style="width: 5.5cm;height: 1.2cm;"> {{ $employer_number->number }} </div>
        </div>
        <div>
            <div class="camp" style="width: 16.5cm;height: 1cm;"> La Paz, {{ Carbon::now()->day }} de {{ Carbon::now()->formatLocalized('%B') }} del {{ Carbon::now()->year }} </div>
        </div>        
    </div>
</div>    
</body>
</html>