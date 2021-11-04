<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigecUser extends Model
{
    protected $connection = 'sigec';
    protected $table = 'users';
    public $timestamps = true;
    protected $hidden = ['encrypted_password', 'reset_password_token', 'reset_password_sent_at'];
    // protected $fillable = ['code', 'name', 'title', 'ci', 'status', 'department_id'];
    protected $fillable = ['id', 'id_oficina', 'username', 'nombre','cedula_identidad', 'cargo','habilitado'];

    public function requests()
    {
        return $this->hasMany(SupplyRequest::class, 'user_id');
    }

    // public function department()
    // {
    //     return $this->belongsTo(SupplyDepartment::class, 'department_id');
    // }
}
