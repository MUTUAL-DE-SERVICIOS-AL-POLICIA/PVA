<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VacationQueue extends Model
{
    public $timestamps = true;
   // public $guarded = ['id'];
    protected $fillable = ['start_date', 'end_date', 'days', 'rest_days', 'max_date', 'employee_id', 'comment'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
