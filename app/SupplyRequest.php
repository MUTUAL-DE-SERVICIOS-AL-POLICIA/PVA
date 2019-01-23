<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'requests';
}
