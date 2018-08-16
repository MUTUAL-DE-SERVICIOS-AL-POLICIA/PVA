<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    //use SoftDeletes;
    protected $dates    = ['deleted_at'];
    public $timestamps  = false;
    public $guarded     = ['id'];
    protected $fillable = ['document_type_id', 'name', 'description'];

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function company()
    {
        return $this->hasMany(Company::class);
    }

    public function position_group()
    {
        return $this->hasMany(PositionGroup::class);
    }
}
