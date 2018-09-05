<?php

namespace App;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['insurance_company_id', 'employee_id', 'position_id', 'contract_type_id', 'contract_mode_id', 'start_date', 'end_date', 'retirement_date', 'retirement_reason_id', 'active', 'rrhh_cite', 'rrhh_cite_date', 'performance_cite', 'insurance_number', 'contract_number', 'hiring_reference_number', 'description'];

	public function employee() {
		return $this->belongsTo(Employee::class);
	}

	public function position() {
		return $this->belongsTo(Position::class);
	}

	public function contract_type() {
		return $this->belongsTo(ContractType::class);
	}

	public function contract_mode() {
		return $this->belongsTo(ContractMode::class);
	}

	public function retirement_reason() {
		return $this->belongsTo(RetirementReason::class);
	}

	public function job_schedules() {
		return $this->belongsToMany(JobSchedule::class);
	}

	public function payrolls() {
		return $this->hasMany(Payroll::class);
	}

	public function insurance_company() {
		return $this->belongsTo(InsuranceCompany::class);
	}

	public function valid_date($year, $month) {
		$first_day_of_month = Carbon::create($year, $month, 1)->format('Y-m-d');
		$last_day_of_month = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

		return Contract::where(function ($query) use ($year, $month, $last_day_of_month, $first_day_of_month) {
			$query
				->orWhere(function ($q) use ($last_day_of_month) {
					$q
						->whereNull('retirement_date')
						->whereNull('end_date')
						->whereDate('start_date', '<=', $last_day_of_month);
				})
				->orWhere(function ($q) use ($year, $month) {
					$q
						->whereNotNull('retirement_date')
						->whereYear('retirement_date', $year)
						->whereMonth('retirement_date', $month);
				})
				->orWhere(function ($q) use ($last_day_of_month, $first_day_of_month) {
					$q
						->whereNull('retirement_date')
						->whereDate('end_date', '>=', $first_day_of_month)
						->whereDate('start_date', '<', $last_day_of_month);
				})
				->orWhere(function ($q) use ($year, $month) {
					$q
						->whereNull('retirement_date')
						->whereYear('start_date', $year)
						->whereMonth('start_date', $month);
				});
		})->leftjoin('employees as e', 'e.id', '=', 'contracts.employee_id')->select('contracts.*')->orderBy('e.last_name')->orderBy('e.mothers_last_name')->orderBy('contracts.start_date')->get();
	}
}
