<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DaysOnVacation extends Model
{
    public $guarded = ['id'];
    protected $fillable = ['departure_id', 'date', 'day'];

    public function departure()
    {
        return $this->HasOne(Departure::class);
    }
}
