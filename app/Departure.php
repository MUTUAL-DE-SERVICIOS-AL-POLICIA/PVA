<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Departure extends Model
{
    //use SoftDeletes;

    public $timestamps  = true;
    public $guarded     = ['id'];
    protected $fillable = ['employee_id', 'departure_reason_id', 'destiny', 'entry_time', 'departure_time', 'return_time', 'start_date', 'end_date', 'description', 'approved'];

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
