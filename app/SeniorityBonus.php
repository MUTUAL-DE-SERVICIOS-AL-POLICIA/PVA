<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeniorityBonus extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = ['from', 'to', 'percentage', 'active'];
}
