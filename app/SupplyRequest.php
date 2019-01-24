<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employee;
use App\SupplyUser;
use Auth;
use Carbon\Carbon;

class SupplyRequest extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'requests';
  public $timestamps = true;
  
  function __construct($employee_id = 0) {    
    if($employee_id == 0) {
      return 0;
    }
    $this->setUser($employee_id);        
    $this->setAdmin();
    $this->status = 'initiation';    
    $this->delivery_date = Carbon::now()->format('Y-m-d H:i:s');    
    $this->invalidate = 0;    
    $last = SupplyRequest::where('admin_id',$this->admin_id)->max('nro_solicitud');
		$this->nro_solicitud = $last+1;    
  }

  private function setUser($employee_id) {    
    $identity_card  = Employee::find($employee_id)->identity_card;    
    $user = SupplyUser::where('ci',$identity_card)->first();
    $this->user_id = $user->id;            
  }
  
  private function setAdmin() {
    $role = \App\Role::where('name','almacenes')->first();				
		$user = \App\User::whereHas('roles',function($query) use ($role) {
			$query->where('role_id',$role->id);
    })->first();
    $admin = SupplyUser::where('ci',$user->identity_card)->first();
    $this->admin_id = $admin->id;
  }
  
  public function subarticles()
  {
    return $this->belongsToMany(Subarticle::class, 'subarticle_requests', 'request_id', 'subarticle_id')->withPivot('id', 'amount', 'amount_delivered', 'total_delivered', 'invalidate');
  }
 
}
