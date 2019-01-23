<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyUser extends Model
{
    protected $connection = 'nsiaf';
    protected $table = 'users';
}
