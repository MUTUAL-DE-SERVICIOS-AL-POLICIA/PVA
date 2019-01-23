<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employee;
//use App\SupplyUser;
use Auth;
class SupplyRequest extends Model
{
  protected $connection = 'nsiaf';
  protected $table = 'requests';

  function __construct() {
    $this->setUser();
    $this->status = 'initiation';
    $this->delivery_date = date('Y-m-d HH:mm:ii');
    $this->invalidate = 0;
  }

  private function setUser() {
    $identity_card  = Employee::find(25)->identity_card;
    $user = 1;//SupplyUser::where('identity_card',$identity_card)->first();
   // $this->user_id = $user->id;
    $this->nro_solicitud = 0;
    $this->admin = 1;
  }

  public function subarticles() {
    //return $this->belongsToMany('App\Subartible')->withPivot('amount','amount_delivered','total_delivered');
  }
 
}
