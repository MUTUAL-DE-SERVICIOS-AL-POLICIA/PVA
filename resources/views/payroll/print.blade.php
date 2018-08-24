<?php
  use \App\Helpers\Util;
  use \App\Http\Controllers\PayrollController as Payroll;
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title->name }} {{ $title->year }}</title>
        <style>
            <?php include public_path('css/all.css') ?>
            <?php include public_path('css/payroll-print.css') ?>
        </style>
    </head>

    <body>
        <div class="header-left">
            <p>{{ $company->name }}</p>
            <p>NIT {{ $company->tax_id_number }}</p>
            <p>{{ $company->address }}</p>
        </div>

        <div class="header-right">
        @if ($company->employer_number)
            <span>No. Patronal CNS: {{ $company->employer_number }}</span>
            <span style="padding-left: 5em;"></span>
        @endif
            <span>{{ $title->report_name }}</span>
        </div>

        <div class="header-center">
          <h2>
              {{ implode(' - ', array_filter([$title->name, $title->subtitle, $title->management_entity, $title->position_group, $title->employer_number])) }}
          </h2>
          <h3>PERSONAL EVENTUAL -
            MES {{ $title->month }} DE {{ $title->year }}</h3>
            <h3>(EXPRESADO EN BOLIVIANOS)</h3>
        </div>

        <div class="header-left">
            <img id="header-image" src="{{ public_path().'/img/logo.png'}}">
        </div>

        <table align="center">
            <thead>
                <tr>
                @switch ($title->report_type)
                    @case ('H')
                        @php ($table_header_space1 = 15)
                        @php ($table_header_space2 = 6)
                        @break
                    @case ('P')
                        @php ($table_header_space1 = 9)
                        @php ($table_header_space2 = 5)
                        @break
                    @case ('T')
                        @php ($table_header_space1 = 3)
                        @php ($table_header_space2 = 1)
                        @php ($table_header_space3 = 1)
                        @php ($table_header_space4 = 4)
                        @php ($table_header_space5 = 2)
                        @php ($table_header_space6 = 3)
                        @break
                @endswitch
                    <th colspan="{{ $table_header_space1 }}" style="border-left: 1px solid white; border-top: 1px solid white; background-color: white;"></th>                    
                    <th colspan="{{ $table_header_space2 }}">{{ $title->table_header }}</th>
                    @if ($title->report_type == 'T')
                        <th colspan="{{ $table_header_space3 }}">{{ $title->table_header2 }}</th>
                        <th colspan="{{ $table_header_space4 }}" style="border-left: 1px solid white; border-top: 1px solid white; background-color: white;"></th>
                        <th colspan="{{ $table_header_space5 }}">{{ $title->table_header3 }}</th>
                        <th colspan="{{ $table_header_space6 }}">{{ $title->table_header4 }}</th>
                    @endif
                </tr>
                <tr>
                    <th width="1%">N°</th>
                    <th width="2%">C.I.</th>
                    <th width="10%">TRABAJADOR</th>
                @if ($title->report_type == 'T')
                    <th width="2%">SUENDO NETO</th>
                    <th width="2%">Minimo No imponible</th>
                    <th width="2%">Diferencia sujeto a impuestos</th>
                    <th width="2%">Impuesto 13% Debito Fiscal</th>
                    <th width="2%">Computo IVA según D.J. Form. 110</th>
                    <th width="2%">13% Sobre 2 Min. Nal.</th>
                    <th width="2%">Fisco</th>
                    <th width="2%">Dependiente</th>
                    <th width="2%">Mes anterior</th>
                    <th width="2%">Actualización</th>
                    <th width="2%">Total</th>
                    <th width="2%">Saldo a favor del dependiente</th>
                    <th width="2%">Saldo Utilizado</th>
                    <th width="2%">Impuesto determinado a pagar</th>
                    <th width="2%">Saldo para el mes siguiente</th>
                @else
                    @if ($title->report_type == 'H')
                        @if (!$title->management_entity)
                        <th width="1%">CUENTA BANCO UNIÓN</th>
                        @endif
                        <th width="1%">FECHA NACIMIENTO</th>
                        <th width="1%">SEXO</th>
                        <th width="1%">CARGO</th>
                    @endif
                        <th width="3%">PUESTO</th>
                    @if (!$title->position_group)
                        <th width="3%">AREA</th>
                    @endif
                        <th width="4%">FECHA DE INGRESO</th>
                    @if ($title->report_type == 'H' && !$title->management_entity)
                        <th width="1%">FECHA VENCIMIENTO CONTRATO</th>
                    @endif
                        <th width="1%">DIAS TRABAJADOS</th>
                    @if ($title->report_type == 'H')
                        <th width="2%">HABER BÁSICO</th>
                    @endif
                        <th width="2%">TOTAL GANADO</th>
                        <th width="1%">AFP</th>
                    @if ($title->report_type == 'H')
                        <th width="1%">Renta vejez {{ $procedure->employee_discount->elderly }}%</th>
                        <th width="1%">Riesgo común {{ $procedure->employee_discount->common_risk }}%</th>
                        <th width="1%">Comisión {{ $procedure->employee_discount->comission }}%</th>
                        <th width="1%">Aporte solidario del asegurado {{ $procedure->employee_discount->solidary }}%</th>
                        <th width="1%">Aporte Nacional solidario 1%, 5%, 10%</th>
                        <th width="1%">TOTAL DESCUENTOS DE LEY</th>
                        <th width="3%">SUELDO NETO</th>
                        <th width="3%">RC IVA {{ $procedure->employee_discount->rc_iva }}%</th>
                        <th width="3%">Desc Atrasos, Faltas y Licencia S/G Haberes</th>
                        <th width="1%">TOTAL DESCUENTOS</th>
                        <th width="3%">LIQUIDO PAGABLE</th>
                    @endif
                    @if ($title->report_type =='P')
                        <th width="1%">CNS {{ $procedure->contribution_insurance_company }}%</th>
                        <th width="1%">Riesgo Profesional {{ $procedure->contribution_professional_risk }}%</th>
                        <th width="1%">Aporte Patronal Solidario {{ $procedure->contribution_employer_solidary }}%</th>
                        <th width="1%">Aporte Patronal para Vivienda {{ $procedure->contribution_employer_housing }}%</th>
                        <th width="3%">TOTAL A PAGAR</th>
                    @endif
                @endif                
                </tr>
            </thead>
            <tbody>
            @php ($total_net_salary = 0)
            @php ($total_min_disponible = 0)
            @php ($total_dif_salario_min_disponible = 0)
            @php ($total_idf = 0)
            @php ($total_iva_110 = 0)
            @php ($total_min_disponible_13 = 0)
            @php ($total_fisco = 0)
            @php ($total_dependiente = 0)
            @php ($total_saldo_mes_anterior = 0)
            @php ($total_actualizacion = 0)
            @php ($total_total = 0)
            @php ($total_saldo_favor_dependiente = 0)
            @php ($total_saldo_utilizado = 0)
            @php ($total_impuesto_pagar = 0)
            @php ($total_saldo_mes_siguiente = 0)
            @php ($index = 1)

            @foreach ($employees as $i => $employee)
                <tr>
                    @if (($i > 0) && ($employee->employee_id != $employees[$i-1]->employee_id))
                        @php (++$index)
                    @endif
                    <td>{{ ++$i }}</td>
                    <td>{{ $employee->ci_ext }}</td>
                    <td class="name">{{ $employee->full_name }}</td>
                @if ($title->report_type == 'T')
                    @php ($tribute = Payroll::tribute_calculation($employee->net_salary, $employee->discount_rc_iva, $employee->previous_month_balance))

                    <td>{{ Util::format_number($employee->net_salary) }}</td>
                    <td>{{ Util::format_number($tribute->min_disponible) }}</td>
                    <td>{{ Util::format_number($tribute->dif_salario_min_disponible) }}</td>
                    <td>{{ round($tribute->idf) }}</td>
                    <td>{{ Util::format_number($tribute->iva_110) }}</td>
                    <td>{{ round($tribute->min_disponible_13) }}</td>
                    <td>{{ round($tribute->fisco) }}</td>
                    <td>{{ round($tribute->dependiente) }}</td>
                    <td>{{ Util::format_number($tribute->saldo_mes_anterior) }}</td>
                    <td>{{ ($tribute->actualizacion) }}</td>
                    <td>{{ Util::format_number($tribute->total) }}</td>
                    <td>{{ Util::format_number($tribute->saldo_favor_dependiente) }}</td>
                    <td>{{ round($tribute->saldo_utilizado) }}</td>
                    <td>{{ round($tribute->impuesto_pagar) }}</td>
                    <td>{{ Util::format_number($tribute->saldo_mes_siguiente) }}</td>
                    @php ($total_net_salary = $total_net_salary + $employee->net_salary)
                    @php ($total_min_disponible = $total_min_disponible + $tribute->min_disponible)
                    @php ($total_dif_salario_min_disponible = $total_dif_salario_min_disponible + $tribute->dif_salario_min_disponible)
                    @php ($total_idf = $total_idf + $tribute->idf)
                    @php ($total_iva_110 = $total_iva_110 + $tribute->iva_110)
                    @php ($total_min_disponible_13 = $total_min_disponible_13 + $tribute->min_disponible_13)
                    @php ($total_fisco = $total_fisco + $tribute->fisco)
                    @php ($total_dependiente = $total_dependiente + $tribute->dependiente)
                    @php ($total_saldo_mes_anterior = $total_saldo_mes_anterior + $tribute->saldo_mes_anterior)
                    @php ($total_actualizacion = $total_actualizacion + $tribute->actualizacion)
                    @php ($total_total = $total_total + $tribute->total)
                    @php ($total_saldo_favor_dependiente = $total_saldo_favor_dependiente + $tribute->saldo_favor_dependiente)
                    @php ($total_saldo_utilizado = $total_saldo_utilizado + $tribute->saldo_utilizado)
                    @php ($total_impuesto_pagar = $total_impuesto_pagar + $tribute->impuesto_pagar)
                    @php ($total_saldo_mes_siguiente = $total_saldo_mes_siguiente + $tribute->saldo_mes_siguiente)
                @else
                    @if ($title->report_type == 'H')
                        @if (!$title->management_entity)
                        <td>{{ $employee->account_number }}</td>
                        @endif
                        <td>{{ $employee->birth_date }}</td>
                        <td>{{ $employee->gender }}</td>
                        <td>{{ $employee->charge }}</td>
                    @endif
                        <td>{{ $employee->position }}</td>
                    @if (!$title->position_group)
                        <td>{{ $employee->position_group }}</td>
                    @endif
                        <td>{{ $employee->start_date }}</td>
                    @if ($title->report_type == 'H' && !$title->management_entity)
                        <td>{{ $employee->end_date }}</td>
                    @endif
                        <td>{{ $employee->worked_days }}</td>
                    @if ($title->report_type == 'H')
                        <td>{{ Util::format_number($employee->base_wage) }}</td>
                    @endif
                        <td>{{ Util::format_number($employee->quotable) }}</td>
                        <td>{{ $employee->management_entity }}</td>
                    @if ($title->report_type == 'H')
                        <td>{{ Util::format_number($employee->discount_old) }}</td>
                        <td>{{ Util::format_number($employee->discount_common_risk) }}</td>
                        <td>{{ Util::format_number($employee->discount_commission) }}</td>
                        <td>{{ Util::format_number($employee->discount_solidary) }}</td>
                        <td>{{ Util::format_number($employee->discount_national) }}</td>
                        <td>{{ Util::format_number($employee->total_amount_discount_law) }}</td>
                        <td>{{ Util::format_number($employee->net_salary) }}</td>
                        <td>{{ Util::format_number($employee->discount_rc_iva) }}</td>
                        <td>{{ Util::format_number($employee->discount_faults) }}</td>
                        <td>{{ Util::format_number($employee->total_discounts) }}</td>
                        <td>{{ Util::format_number($employee->payable_liquid) }}</td>
                    @endif
                    @if ($title->report_type == 'P')
                        <td>{{ Util::format_number($employee->contribution_insurance_company) }}</td>
                        <td>{{ Util::format_number($employee->contribution_professional_risk) }}</td>
                        <td>{{ Util::format_number($employee->contribution_employer_solidary) }}</td>
                        <td>{{ Util::format_number($employee->contribution_employer_housing) }}</td>
                        <td>{{ Util::format_number($employee->total_contributions) }}</td>
                    @endif
                @endif
                </tr>
            @endforeach
                <tr class="total" style="height: 45px;">
                @switch ($title->report_type)
                    @case ('H')
                        @if ($title->position_group)
                            @php ($table_footer_space1 = 11)
                        @elseif ($title->management_entity)
                            @php ($table_footer_space1 = 10)
                        @else
                            @php ($table_footer_space1 = 12)
                        @endif
                        @break
                    @case ('P')
                        @if ($title->position_group)
                            @php ($table_footer_space1 = 6)
                        @else
                            @php ($table_footer_space1 = 7)
                        @endif
                        @break
                    @case ('T')
                        @if ($title->position_group)
                            @php ($table_footer_space1 = 2)
                        @else
                            @php ($table_footer_space1 = 3)
                        @endif
                        @break
                @endswitch
                <td class="footer" colspan="{{ $table_footer_space1 }}">TOTAL PLANILLA ({{ $index }} {{ ($index == 1) ? 'FUNCIONARIO' : 'FUNCIONARIOS'}})</td>
                @if ($title->report_type == 'H')
                    <td class="footer">{{ Util::format_number($total_discounts->base_wage) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->quotable) }}</td>
                    <td class="footer"></td>
                    <td class="footer">{{ Util::format_number($total_discounts->discount_old) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->discount_common_risk) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->discount_commission) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->discount_solidary) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->discount_national) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->total_amount_discount_law) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->net_salary) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->discount_rc_iva) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->total_faults) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->total_discounts) }}</td>
                    <td class="footer">{{ Util::format_number($total_discounts->payable_liquid) }}</td>
                @endif
                @if ($title->report_type == 'P')
                    <td class="footer">{{ Util::format_number($total_contributions->quotable) }}</td>
                    <td class="footer"></td>
                    <td class="footer">{{ Util::format_number($total_contributions->contribution_insurance_company) }}</td>
                    <td class="footer">{{ Util::format_number($total_contributions->contribution_professional_risk) }}</td>
                    <td class="footer">{{ Util::format_number($total_contributions->contribution_employer_solidary) }}</td>
                    <td class="footer">{{ Util::format_number($total_contributions->contribution_employer_housing) }}</td>
                    <td class="footer">{{ Util::format_number($total_contributions->total_contributions) }}</td>
                @endif
                @if ($title->report_type == 'T')
                    <td class="footer"> {{ Util::format_number($total_net_salary) }} </td>
                    <td class="footer"> {{ Util::format_number($total_min_disponible) }} </td>
                    <td class="footer"> {{ Util::format_number($total_dif_salario_min_disponible) }} </td>
                    <td class="footer"> {{ Util::format_number($total_idf) }} </td>
                    <td class="footer"> {{ Util::format_number($total_iva_110) }} </td>
                    <td class="footer"> {{ Util::format_number($total_min_disponible_13) }} </td>
                    <td class="footer"> {{ Util::format_number($total_fisco) }} </td>
                    <td class="footer"> {{ Util::format_number($total_dependiente) }} </td>
                    <td class="footer"> {{ Util::format_number($total_saldo_mes_anterior) }} </td>
                    <td class="footer"> {{ Util::format_number($total_actualizacion) }} </td>
                    <td class="footer"> {{ Util::format_number($total_total) }} </td>
                    <td class="footer"> {{ Util::format_number($total_saldo_favor_dependiente) }} </td>
                    <td class="footer"> {{ Util::format_number($total_saldo_utilizado) }} </td>
                    <td class="footer"> {{ Util::format_number($total_impuesto_pagar) }} </td>
                    <td class="footer"> {{ Util::format_number($total_saldo_mes_siguiente) }} </td>
                @endif
                </tr>
            
            </tbody>
        </table>
    </body>

</html>