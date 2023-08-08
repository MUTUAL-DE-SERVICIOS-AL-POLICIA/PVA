<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
  public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['from', 'to', 'days', 'active'];
}
