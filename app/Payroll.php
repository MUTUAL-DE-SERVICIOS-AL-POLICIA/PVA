<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['code', 'unworked_days', 'procedure_id', 'contract_id', 'employee_id', 'charge_id', 'position_group_id', 'position_id', 'faults', 'next_month_balance', 'previous_month_balance', 'rc_iva'];

	public function procedure() {
		return $this->belongsTo(Procedure::class);
	}

	public function contract() {
		return $this->belongsTo(Contract::class);
	}

	public function employee() {
		return $this->belongsTo(Employee::class);
	}

	public function charge() {
		return $this->belongsTo(Charge::class);
	}

	public function position_group() {
		return $this->belongsTo(PositionGroup::class);
	}

	public function position() {
		return $this->belongsTo(Position::class);
	}
	public function payroll_date() {
		$year = $this->procedure->year;
		$month = str_pad($this->procedure->month_id, 2, '0', STR_PAD_LEFT);
		return Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-02");
	}
}
