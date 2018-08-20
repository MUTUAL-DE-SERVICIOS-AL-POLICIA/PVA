<?php

namespace App;

use App\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model {
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	public $timestamps = false;
	public $guarded = ['id'];
	protected $fillable = ['city_identity_card_id', 'management_entity_id', 'identity_card', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband', 'birth_date', 'city_birth_id', 'account_number', 'country_birth', 'nua_cua', 'gender', 'location', 'zone', 'street', 'address_number', 'phone_number', 'active'];

	public function fullName($style = "uppercase", $order = "name_first") {
		return Util::fullName($this, $style, $order);
	}

	public function city_identity_card() {
		return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
	}

	public function city_birth() {
		return $this->belongsTo(City::class, 'city_birth_id', 'id');
	}

	public function management_entity() {
		return $this->belongsTo(ManagementEntity::class);
	}

	public function contracts() {
		return $this->hasMany(Contract::class);
	}
}
