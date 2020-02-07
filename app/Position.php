<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon;

class Position extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['name', 'item', 'charge_id', 'position_group_id', 'active'];

	public function depends_from()
	{
		return $this->belongsToMany(Position::class, 'dependency_positions', 'dependent_id', 'superior_id');
	}

	public function dependents()
	{
		return $this->belongsToMany(Position::class, 'dependency_positions', 'superior_id', 'dependent_id');
	}

	public function consultants()
	{
		return $this->belongsToMany(ConsultantPosition::class, 'dependency_positions', 'superior_id', 'consultant_id');
	}

	public function charge()
	{
		return $this->belongsTo(Charge::class);
	}

	public function position_group()
	{
		return $this->belongsTo(PositionGroup::class);
	}

	public function contracts()
	{
		return $this->hasMany(Contract::class);
	}

	public function payrolls()
	{
		return $this->hasMany(Payroll::class);
    }

    public function get_employee()
    {
        $contracts = $this->contracts()->where(function($query) {
            $query->orWhere(function($q) {
                $q->whereNull('end_date')->whereNull('retirement_date');
            })->orWhere(function($q) {
                $q->whereDate('end_date', '>=', Carbon::now()->endOfDay());
            })->orWhere(function($q) {
                $q->whereDate('retirement_date', '>=', Carbon::now()->endOfDay());
            });
        })->get();
        if ($contracts->count() != 1) {
            return (object)[];
        } else {
        }
        return $contracts->first()->employee;
    }
}
