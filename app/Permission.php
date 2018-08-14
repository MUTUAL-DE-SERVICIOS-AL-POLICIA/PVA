<?php

namespace App;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['name', 'display_name', 'description'];
}
