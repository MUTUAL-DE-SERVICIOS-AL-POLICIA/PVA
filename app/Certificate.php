<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    //use SoftDeletes;

    public $timestamps  = true;
    public $guarded     = ['id'];
    protected $fillable = ['document_type_id', 'correlative', 'year', 'data'];

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function departures()
    {
    	return $this->hasMany(Departure::class, 'certificate_id', 'id');
    }
}
