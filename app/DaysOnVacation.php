<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DaysOnVacation extends Model
{
    public $guarded = ['id'];
    protected $fillable = ['departure_id', 'date', 'day', 'period'];

    public function departure()
    {
        return $this->belongsTo(Departure::class);
    }
}
