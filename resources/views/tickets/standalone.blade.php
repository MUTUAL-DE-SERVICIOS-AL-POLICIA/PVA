<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <style>
    <?php include public_path('css/ticket-standalone.min.css') ?>
  </style>
</head>

<body>
  @foreach ($payrolls as $payroll)
  <div class="ticket">
    <!-- LEFT-TICKET -->
    <div class="left-ticket">
      <div class="font-normal left-header left-header-title">{{ mb_strtoupper($company->name) }}</div>
      <div class="font-tiny left-header">{{ ucwords(strtolower($company->address)) }}</div>
      <div class="left-header-subtitle">
        <table>
          <tr>
            <td class="font-tiny left-nit">{{ $payroll->employer_number }}</td>
            <td class="font-tiny">{{ $company->tax_number }}</td>
          </tr>
        </table>
      </div>
      <div class="left-employee-box">
        <table>
          <tr>
            <td class="font-normal left-box-code">{{ $payroll->code }}</td>
            <td class="left-employee-space-normal"></td>
            <td class="font-normal" style="white-space: nowrap;">{{ $payroll->account_number ? 'ABONO EN CUENTA' : 'PAGO EN CHEQUE' }}</td>
          </tr>
          <tr>
            <td class="font-normal left-box-code">PAGO DE HABERES {{ $payroll->month_shortened }} {{ $payroll->year }}</td>
            <td class="left-employee-space-normal"></td>
            <td class="font-normal">{{ $payroll->worked_days }}</td>
          </tr>
          <tr>
            <td class="left-employee-space-tiny"></td>
          </tr>
          <tr>
            <td class="font-normal left-box-code">{{ $payroll->ci_ext }}</td>
            <td class="font-normal" colspan="2">{{ $payroll->full_name }}</td>
          </tr>
          <tr>
          @if ($payroll->account_number)
            <td class="font-normal left-box-code">{{ $payroll->account_number }}</td>
          @else
            <td class="font-normal left-box-code">S/N</td>
          @endif
            <td class="font-normal" colspan="2">{{ $payroll->birth_date }}</td>
          </tr>
          <tr>
            <td class="font-normal left-box-code">{{ $payroll->management_entity }}</td>
            <td class="font-normal" colspan="2">{{ $payroll->nua_cua }}</td>
          </tr>
        </table>
      </div>
      <div class="font-normal left-charge">{{ mb_strtoupper($payroll->position) }}</div>
      <div class="left-discounts-box">
        <table>
          <tr>
            <td class="font-large left-discounts-first-row">SUELDOS</td>
            <td class="font-large left-discounts-second-row" align="right">{{ Util::formatMoney($payroll->quotable) }}</td>
            <td class="font-large left-discounts-third-row">AFP.RV.{{ $procedure->employee_discount->elderly * 100 }}%</td>
            <td class="font-large" align="right">{{ Util::formatMoney($payroll->discount_old) }}</td>
          </tr>
          <tr>
            <td class="font-large left-discounts-first-row"></td>
            <td class="font-large left-discounts-second-row" align="right"></td>
            <td class="font-large left-discounts-third-row">AFP.RC.{{ $procedure->employee_discount->common_risk * 100 }}%</td>
            <td class="font-large" align="right">{{ Util::formatMoney($payroll->discount_common_risk) }}</td>
          </tr>
          <tr>
            <td class="font-large left-discounts-first-row"></td>
            <td class="font-large left-discounts-second-row" align="right"></td>
            <td class="font-large left-discounts-third-row">AFP.CM.{{ $procedure->employee_discount->comission * 100 }}%</td>
            <td class="font-large" align="right">{{ Util::formatMoney($payroll->discount_commission) }}</td>
          </tr>
          <tr>
            <td class="font-large left-discounts-first-row"></td>
            <td class="font-large left-discounts-second-row" align="right"></td>
            <td class="font-large left-discounts-third-row">AFP.SOL.ASE.{{ $procedure->employee_discount->solidary * 100 }}%</td>
            <td class="font-large" align="right">{{ Util::formatMoney($payroll->discount_solidary) }}</td>
          </tr>
          <tr>
            <td class="font-large left-discounts-first-row"></td>
            <td class="font-large left-discounts-second-row" align="right"></td>
            <td class="font-large left-discounts-third-row">RC-IVA</td>
            <td class="font-large" align="right">{{ Util::formatMoney($payroll->discount_rc_iva) }}</td>
          </tr>
          <tr>
            <td class="font-large left-discounts-first-row"></td>
            <td class="font-large left-discounts-second-row" align="right"></td>
            <td class="font-large left-discounts-third-row">OTROS DESCUENTOS</td>
            <td class="font-large" align="right">{{ Util::formatMoney($payroll->discount_faults) }}</td>
          </tr>
          <tr>
            <td class="left-discounts-vertical-space"></td>
          </tr>
          <tr>
            <td class="font-large left-discounts-first-row"></td>
            <td class="font-large left-discounts-second-row" align="right">{{ Util::formatMoney($payroll->quotable) }}</td>
            <td class="font-large left-discounts-third-row"></td>
            <td class="font-large" align="right">{{ Util::formatMoney($payroll->total_discounts) }}</td>
          </tr>
          <tr>
            <td class="font-huge left-discounts-first-row"></td>
            <td class="font-huge left-discounts-second-row" align="right"></td>
            <td class="font-huge left-discounts-third-row"></td>
            <td class="font-huge" align="right">{{ Util::formatMoney($payroll->payable_liquid) }}</td>
          </tr>
        </table>
      </div>
      <div class="left-image">
        <img src="data:image/png;base64, {{ $payroll->code_image }}" width="180pt" height="38pt">
      </div>
    </div>

    <!-- RIGHT-TICKET -->
    <div class="right-ticket">
      <div class="font-normal right-text right-header-title">{{ mb_strtoupper($company->name) }}</div>
      <div class="font-normal right-text right-header-title">{{ ucwords(strtolower($company->address)) }}</div>
      <div class="right-header-subtitle">
        <table>
          <tr>
            <td class="font-normal right-text right-nit">{{ $payroll->employer_number }}</td>
            <td class="font-normal right-text">{{ $company->tax_number }}</td>
          </tr>
        </table>
      </div>
      <div class="right-content">
        <div class="font-normal right-text right-text-right">{{ $payroll->code }}</div>
        <div class="font-normal right-text right-text-right">{{ $payroll->account_number ? 'ABONO EN CUENTA' : 'PAGO EN CHEQUE' }}</div>
        <div class="font-normal right-text right-text-right">PAGO DE HABERES {{ $payroll->month_shortened }} {{ $payroll->year }}</div>
        <div class="font-normal right-text right-text-right right-worked-days">{{ $payroll->worked_days }}</div>
        <div class="font-normal right-text right-text-right">{{ $payroll->ci_ext }}</div>
        <div class="font-normal right-text-left right-name">{{ $payroll->full_name }}</div>
        @if ($payroll->account_number)
        <div class="font-normal right-text-left">{{ $payroll->account_number }}</div>
        @else
        <div class="font-normal right-text-left">S/N</div>
        @endif
        <div class="font-normal right-text-left">{{ $payroll->birth_date }}</div>
        <div class="font-normal right-text-left">{{ $payroll->management_entity }}</div>
        <div class="font-normal right-text-left right-nua">{{ $payroll->nua_cua }}</div>
        <div class="font-normal right-text-left right-charge">{{ mb_strtoupper($payroll->position) }}</div>
      </div>
      <div class="font-huge" align="right">{{ Util::formatMoney($payroll->payable_liquid) }}</div>
      <div>
          <img class="right-image" src="data:image/png;base64, {{ $payroll->code_image }}"  width="180pt" height="38pt">
        </div>
      </div>
    </div>
  </div>
  @endforeach
</body>

</html>