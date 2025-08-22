<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VacationQueue extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = ['start_date', 'end_date', 'days', 'rest_days', 'max_date', 'employee_id', 'comment'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
