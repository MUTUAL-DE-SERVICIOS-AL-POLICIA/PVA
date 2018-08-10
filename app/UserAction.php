<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAction extends Model {
	use SoftDeletes;
	public $timestamps = true;
	public $guarded = ['id'];
	protected $dates = ['deleted_at'];
	protected $fillable = ['user_id', 'method', 'path', 'data'];

	public function user() {
		return $this->belongsTo(User::class);
	}
}