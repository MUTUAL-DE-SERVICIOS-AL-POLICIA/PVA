<?php
use \App\Helpers\Util;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title->name }} {{ $procedure->year }}</title>
    </head>
    <body>
        <div>
            <div class="header-left">
                <img id="header-image" src="{{ public_path().'/img/logo.png'}}">
            </div>
            <div class="header-center">
                <h2>
                    {{ $title->name }}
                </h2>
                <h3>{{ $title->subtitle }} - {{ $procedure->month->name }} DE {{ $procedure->year }}</h3>
                <h3>(EXPRESADO EN BOLIVIANOS)</h3>
            </div>
            <div class="header-right">
                <p>{{ $company->shortened }}</p>
                <p>NIT {{ $company->tax_number }}</p>
            </div>
        </div>
        <table align="center">
            <thead>
                <tr>
                    <th width="5%">N°</th>
                    <th width="35%">NOMBRE</th>
                    <th width="15%">DÍAS TRABAJADOS</th>
                    <th width="15%">MONTO DIARIO</th>
                    <th width="15%">TOTAL A PAGAR</th>
                    <th width="15%">FIRMA</th>
                </tr>
            </thead>
            <tbody>
            @php($total_payment = 0)
            @foreach ($employees as $i => $employee)
                <tr style="height: 45px;">
                    @php($worked_days = $procedure->worked_days - $employee->unworked_days)
                    @php($payment = $procedure->employer_contribution->living_expenses * $worked_days)
                    @php($total_payment += $payment)
                    <td>{{ ++$i }}</td>
                    <td class="name">{{ $employee->full_name }}</td>
                    <td>{{ $worked_days }}</td>
                    <td>{{ $procedure->employer_contribution->living_expenses }}</td>
                    <td>{{ Util::format_number($payment) }}</td>
                    <td></td>
                </tr>
                @php($index = $i)
            @endforeach
                <tr class="total" style="height: 45px;">
                    @php ($table_footer_space1 = 4)
                    <td class="footer" colspan="{{ $table_footer_space1 }}">TOTAL PLANILLA ({{ count($employees) == 0 ? 0 : $index }} {{ count($employees) == 0 ? 'FUNCIONARIOS' : ($index == 1 ? 'FUNCIONARIO' : 'FUNCIONARIOS')}})</td>
                    <td class="footer">{{ Util::format_number($total_payment) }}</td>
                    <td class="footer"></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>