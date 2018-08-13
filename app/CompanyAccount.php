<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyAccount extends Model {
	use SoftDeletes;

	public $timestamps = true;
	public $guarded = ['id'];
	protected $dates = ['deleted_at'];
	protected $fillable = ['account', 'financial_entity', 'description', 'company_id'];
	protected $hidden = ['company_id'];

	public function company() {
		return $this->belongsTo(Company::class);
	}
}
