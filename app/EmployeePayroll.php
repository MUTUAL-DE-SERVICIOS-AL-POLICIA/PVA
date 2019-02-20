<?php

namespace App;

use App\Helpers\Util;
use Carbon;
use \Milon\Barcode\DNS2D;

class EmployeePayroll
{
  function __construct($payroll)
  {
    $contract = $payroll->contract;
    $employee = $contract->employee;

    $this->code_image = null;

    // Common data
    $this->employee_id = $employee->id;
    $this->year = $payroll->procedure->year;
    $this->month_id = $payroll->procedure->month->order;
    $this->month_shortened = $payroll->procedure->month->shortened;
    $this->payroll_id = $payroll->id;
    $this->previous_month_balance = $payroll->previous_month_balance;
    $this->next_month_balance = $payroll->next_month_balance;
    $this->nua_cua = $employee->nua_cua;
    $this->ci = $employee->identity_card;
    $this->id_ext = $employee->city_identity_card->shortened;
    $this->insurance_company_id = $contract->insurance_company_id;
    $this->ci_ext = $this->ci . ' ' . $this->id_ext;
    $this->first_name = $employee->first_name;
    $this->second_name = $employee->second_name;
    $this->last_name = $employee->last_name;
    $this->mothers_last_name = $employee->mothers_last_name;
    $this->full_name = implode(" ", [$this->last_name, $this->mothers_last_name, $this->first_name, $this->second_name]);
    $this->account_number = $employee->account_number;
    $this->birth_date = Carbon::parse($employee->birth_date)->format('d/m/Y');
    $this->gender = $employee->gender;
    $this->charge = $contract->position->charge->name;
    $this->position = $contract->position->name;
    $this->start_date = Carbon::parse($contract->start_date)->format('d/m/Y');
    $this->end_date = is_null($contract->end_date) ? 'Indefinido' : Carbon::parse($contract->end_date)->format('d/m/Y');
    $this->retirement_date = is_null($contract->retirement_date) ? null : Carbon::parse($contract->retirement_date)->format('d/m/Y');
    $this->base_wage = floatval($payroll->position->charge->base_wage);
    $this->management_entity = $employee->management_entity->name;
    $this->management_entity_id = $employee->management_entity->id;
    $this->unworked_days = $payroll->unworked_days;
    $this->worked_days = $this->worked_days($payroll);
    $this->ovt = (object)[
      'insurance_company_id' => $contract->insurance_company->ovt_id,
      'management_entity_id' => $employee->management_entity->ovt_id,
      'contract_mode' => $employee->last_contract()->contract_mode->ovt_id,
      'contract_type' => $employee->last_contract()->contract_type->ovt_id,
    ];

    // Payable template
    $this->discount_old = null;
    $this->discount_common_risk = null;
    $this->discount_commission = null;
    $this->discount_solidary = null;
    $this->discount_national = null;
    $this->total_amount_discount_law = null;
    $this->net_salary = null;
    $this->discount_rc_iva = null;
    $this->discount_faults = null;
    $this->total_discounts = null;
    $this->payable_liquid = null;
    $this->employeeDiscounts($payroll);

    // Employer template
    $this->contribution_insurance_company = null;
    $this->contribution_professional_risk = null;
    $this->contribution_employer_solidary = null;
    $this->contribution_employer_housing = null;
    $this->total_contributions = null;
    $this->employerContribution($payroll);

    // Extra data
    $this->position_group = $contract->position->position_group->name;
    $this->position_group_id = $contract->position->position_group->id;
    $this->employer_number = $payroll->position_group->company_address->city->employer_number->number;
    $this->employer_number_id = $payroll->position_group->company_address->city->employer_number->id;
    $this->valid_contract = Util::valid_contract($payroll, null);
  }

  public function setZeroAccounts()
  {
    $this->base_wage = 0;
    $this->quotable = 0;
    $this->code = 0;
    $this->discount_old = 0;
    $this->discount_common_risk = 0;
    $this->discount_commission = 0;
    $this->discount_solidary = 0;
    $this->discount_national = 0;
    $this->total_amount_discount_law = 0;
    $this->discount_commission = 0;
    $this->net_salary = 0;
    $this->discount_rc_iva = 0;
    $this->discount_faults = 0;
    $this->total_discounts = 0;
    $this->payable_liquid = 0;
    $this->contribution_insurance_company = 0;
    $this->contribution_professional_risk = 0;
    $this->contribution_employer_solidary = 0;
    $this->contribution_employer_housing = 0;
    $this->total_contributions = 0;
  }

  private function employeeDiscounts($payroll)
  {
    $this->id = $payroll->id;
    $this->quotable = $this->base_wage * $this->worked_days / 30;
    $this->code = $payroll->code;
    $this->discount_old = $this->quotable * $payroll->procedure->employee_discount->elderly;
    $this->discount_common_risk = $this->quotable * $payroll->procedure->employee_discount->common_risk;
    $this->discount_commission = $this->quotable * $payroll->procedure->employee_discount->comission;
    $this->discount_solidary = $this->quotable * $payroll->procedure->employee_discount->solidary;
    $this->discount_national = $this->quotable * $payroll->procedure->employee_discount->national;
    $this->total_amount_discount_law = $this->discount_old + $this->discount_common_risk + $this->discount_commission + $this->discount_solidary + $this->discount_national;
    $this->net_salary = $this->quotable - $this->total_amount_discount_law;
    $this->discount_rc_iva = $payroll->rc_iva;
    $this->discount_faults = $payroll->faults;
    $this->total_discounts = $this->total_amount_discount_law + $this->discount_faults;
    $this->payable_liquid = round(($this->quotable - $this->total_discounts), 2);
  }

  private function employerContribution($payroll)
  {
    $this->contribution_insurance_company = $this->quotable * $payroll->procedure->employer_contribution->insurance_company;
    $this->contribution_professional_risk = $this->quotable * $payroll->procedure->employer_contribution->professional_risk;
    $this->contribution_employer_solidary = $this->quotable * $payroll->procedure->employer_contribution->solidary;
    $this->contribution_employer_housing = $this->quotable * $payroll->procedure->employer_contribution->housing;
    $this->total_contributions = round(($this->contribution_insurance_company + $this->contribution_professional_risk + $this->contribution_employer_solidary + $this->contribution_employer_housing), 2);
  }

  public function mergePayroll($employe_payroll)
  {
    $this->worked_days += $employe_payroll->worked_days;
    $this->quotable += $employe_payroll->quotable;
    $this->discount_old += $employe_payroll->discount_old;
    $this->discount_common_risk += $employe_payroll->discount_common_risk;
    $this->discount_commission += $employe_payroll->discount_commission;
    $this->discount_solidary += $employe_payroll->discount_solidary;
    $this->discount_national += $employe_payroll->discount_national;
    $this->total_amount_discount_law += $employe_payroll->total_amount_discount_law;
    $this->net_salary += $employe_payroll->net_salary;
    $this->discount_rc_iva += $employe_payroll->discount_rc_iva;
    $this->discount_faults += $employe_payroll->discount_faults;
    $this->total_discounts += $employe_payroll->total_discounts;
    $this->payable_liquid += $employe_payroll->payable_liquid;
    return $this;
  }

  public function generateImage()
  {
    $this->code_image = DNS2D::getBarcodePNG(($this->employee_id . ' ' . $this->full_name . ' ' . $this->position . ' ' . $this->month_id . ' ' . $this->year . ' ' . $this->base_wage . ' ' . $this->total_discounts . ' ' . $this->payable_liquid), "PDF417", 3, 33, array(1, 1, 1));
    return $this;
  }

  public function setWorkedDays($worked_days)
  {
    $this->worked_days = $worked_days;
    return $this;
  }

  public function getWorkedDays()
  {
    return $this->worked_days;
  }

  public function setCodeImage($code_image)
  {
    $this->code_image = $code_image;
  }

  public function setValidContact($valid_contract)
  {
    $this->valid_contract = $valid_contract;
  }

  private function worked_days($payroll)
  {
    $contract = $payroll->contract;

    $payroll_date = Carbon::create($payroll->procedure->year, $payroll->procedure->month->order);

    $start_date = Carbon::parse($contract->start_date . 'T0:0:0');

    $end_date = ($contract->end_date == null && $contract->retirement_date == null) ? $payroll_date->endOfMonth() : Carbon::parse($contract->end_date . 'T23:59:59.999999');

    if ($this->retirement_date != null) {
      $retirement_date = Carbon::parse($contract->retirement_date . 'T23:59:59.999999');
      if ($retirement_date->year == $payroll_date->year && $retirement_date->month == $payroll_date->month) {
        $end_date = $retirement_date;
      }
    }

    $last_day_of_month = Carbon::create($payroll_date->year, $payroll_date->month)->endOfMonth()->day;
    $worked_days = 0;

    if (is_null($contract->end_date) && is_null($contract->retirement_date) && $start_date->year <= $payroll_date->year) {
      if ($start_date->year == $payroll_date->year) {
        if ($start_date->month < $payroll_date->month) {
          $worked_days = 30;
        } else {
          $worked_days = 30 - $start_date->day + 1;
        }
      } else {
        $worked_days = 30;
      }
    } else if ($start_date->year == $end_date->year && $start_date->month == $end_date->month) {
      if ($end_date->day == $last_day_of_month && ($last_day_of_month < 30 || $last_day_of_month > 30)) {
        $worked_days = 30 - $start_date->day;
      } else {
        $worked_days = $end_date->day - $start_date->day;
      }
      $worked_days += 1;
    } elseif ($start_date->year <= $payroll_date->year && $start_date->month == $payroll_date->month) {
      $worked_days = 30 - $start_date->day + 1;
    } elseif ($end_date->year >= $payroll_date->year && $end_date->month == $payroll_date->month) {
      $worked_days = $end_date->day > 30 ? 30 : $end_date->day;
    } elseif (($start_date->year <= $payroll_date->year && $start_date->month < $payroll_date->month) || ($end_date->year >= $payroll_date->year && $end_date->month > $payroll_date->month)) {
      $worked_days = 30;
    } else {
      $worked_days = 0;
    }
    if ($payroll->unworked_days > $worked_days) {
      return 0;
    } else {
      return ($worked_days - $payroll->unworked_days);
    }
  }
}

