<?php

namespace App;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['name', 'display_name', 'description'];
}
