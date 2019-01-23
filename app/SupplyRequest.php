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
  
  function __construct($employee_id = 0) {    
    $this->setUser($employee_id);        
    $this->status = 'initiation';    
    $this->delivery_date = Carbon::now()->format('Y-m-d H:i:s');    
    $this->invalidate = 0;
  }

  private function setUser($employee_id) {
    if($employee_id == 0) {
      return 0;
    }
    $identity_card  = Employee::find($employee_id)->identity_card;    
    $user = SupplyUser::where('ci',$identity_card)->first();
    $this->user_id = $user->id;
    $this->nro_solicitud = 0;
    $this->admin_id = 1;
  }
  
  public function subarticles()
  {
    return $this->belongsToMany(Subarticle::class, 'subarticle_requests', 'request_id', 'subarticle_id')->withPivot('id', 'amount', 'amount_delivered', 'total_delivered', 'invalidate');
  }
 
}
