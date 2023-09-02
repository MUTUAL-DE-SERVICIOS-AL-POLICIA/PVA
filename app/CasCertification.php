<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CasCertification extends Model
{
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = ['years', 'months', 'days', 'certification_number', 'issue_date', 'active', 'employee_id', 'for_vacation'];

    public function employee()
    {
        return $this->HasOne(Employee::class);
    }
}
