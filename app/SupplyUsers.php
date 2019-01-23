<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyUsers extends Model
{
    protected $connection = 'nsiaf';
    protected $table = 'users';
}
