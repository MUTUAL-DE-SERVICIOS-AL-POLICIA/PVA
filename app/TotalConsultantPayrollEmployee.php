<?php

namespace App;

class TotalConsultantPayrollEmployee
{
	function __construct()
	{
		$this->base_wage = 0;
		$this->net_salary = 0;
		$this->payable_liquid = 0;
		$this->total_faults = 0;
	}

	public function add_base_wage($value)
	{
		$this->base_wage += $value;
	}
	public function add_net_salary($value)
	{
		$this->net_salary += $value;
	}
	public function add_total_faults($value)
	{
		$this->total_faults += $value;
	}
	public function add_payable_liquid($value)
	{
		$this->payable_liquid += $value;
	}

	public function subtract_base_wage($value)
	{
		$this->base_wage -= $value;
	}
	public function subtract_net_salary($value)
	{
		$this->net_salary -= $value;
	}
	public function subtract_total_faults($value)
	{
		$this->total_faults -= $value;
	}
	public function subtract_payable_liquid($value)
	{
		$this->payable_liquid -= $value;
	}
}
