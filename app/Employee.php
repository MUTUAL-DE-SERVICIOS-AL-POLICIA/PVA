<?php

namespace App;

use App\Helpers\Util;
use App\Http\Controllers\Api\V1\SeniorityBonusController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\CarbonImmutable;

class Employee extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  public $timestamps = true;
  public $guarded = ['id'];
  protected $fillable = ['city_identity_card_id', 'management_entity_id', 'identity_card', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband', 'birth_date', 'city_birth_id', 'account_number', 'country_birth', 'nua_cua', 'gender', 'location', 'zone', 'street', 'address_number', 'phone_number', 'landline_number', 'active', 'addmission_date'];

  public function fullName($style = "uppercase", $order = "name_first")
  {
    return Util::fullName($this, $style, $order);
  }

  public function getFullNameAttribute()
  {
    return rtrim(preg_replace('/[[:blank:]]+/', ' ', join(' ', [$this->last_name, $this->mothers_last_name, $this->surname_husband, $this->first_name, $this->second_name])));
  }

  public function city_identity_card()
  {
    return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
  }

  public function city_birth()
  {
    return $this->belongsTo(City::class, 'city_birth_id', 'id');
  }

  public function management_entity()
  {
    return $this->belongsTo(ManagementEntity::class);
  }

  public function contracts()
  {
    return $this->hasMany(Contract::class);
  }

  public function contract_in_date($date)
{
    $status = $this->consultant();
    $date = \Carbon\Carbon::parse($date);

    if ($status === true) {
        return ConsultantContract::whereEmployeeId($this->id)
            ->where('start_date', '<=', $date)
            ->orderBy('start_date', 'DESC')
            ->first();
    } elseif ($status === 2) {
        return AssistantContract::whereEmployeeId($this->id)
            ->where('start_date', '<=', $date)
            ->orderBy('start_date', 'DESC')
            ->first();
    } elseif ($status === false) {
        return Contract::whereEmployeeId($this->id)
            ->where('start_date', '<=', $date)
            ->orderBy('start_date', 'DESC')
            ->first();
    }
    return null;
}

  public function consultant_contracts()
  {
    return $this->hasMany(ConsultantContract::class);
  }

  public function payrolls()
  {
    return $this->hasMany(Payroll::class);
  }

  public function total_contracts()
  {
    return $this->contracts->count() + $this->consultant_contracts->count() + $this->assistant_contracts->count();
  }

  public function consultant()
  {
    $contract = $this->last_contract();
    $consultant_contract = $this->last_consultant_contract();
    $assistant_contract = $this->last_assistant_contract();
    $status = null;
    if($contract && $consultant_contract && $assistant_contract)
    {
      if(Carbon::now()->between($consultant_contract->start_date, $consultant_contract->end_date) && $consultant_contract->retirement_date != null && $consultant_contract->active)
        $contract = null;
      elseif(Carbon::now() > $contract->start_date && $contract->retirement_date == null && $contract->active)
        $consultant_contract = null;
    }
    if($consultant_contract)
    {
      if($consultant_contract->retirement_date == null && $consultant_contract->end_date != null && Carbon::now()->between($consultant_contract->start_date, $consultant_contract->end_date) && $consultant_contract->active)
        $status = true;
    }
    elseif($contract)
    {
      if($contract->retirement_date == null && Carbon::now()->greaterThan($contract->start_date) && $contract->active)
        $status = false;
    }
    elseif($assistant_contract)
    {
      if($assistant_contract->retirement_date == null && Carbon::now()->greaterThan($assistant_contract->start_date) && $assistant_contract->active)
        $status = 2;
    }
    return $status;
  }

  public function consultant_payrolls()
  {
    return $this->hasMany(ConsultantPayroll::class);
  }

  public function first_contract()
  {
    return $this->contracts()->orderBy('start_date', 'ASC')->first();
  }

  public function first_consultant_contract()
  {
    return $this->consultant_contracts()->orderBy('start_date', 'ASC')->first();
  }

  public function last_contract()
  {
    return $this->contracts()->orderBy('start_date', 'DESC')->where('active', true)->first();
  }

  public function last_consultant_contract()
  {
    return $this->consultant_contracts()->orderBy('start_date', 'DESC')->where('active', true)->first();
  }

  public function before_last_contract()
  {
    return $this->contracts()->orderBy('start_date', 'DESC')->skip(1)->first();
  }

  public function before_last_consultant_contract()
  {
    return $this->consultant_contracts()->orderBy('start_date', 'DESC')->skip(1)->first();
  }

  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function departures()
  {
    return $this->hasMany(Departure::class);
  }

  public function monthly_remaining_departures()
  {
    $now = Carbon::today()->day;
    $start_date = CarbonImmutable::today()->day(20);
    if ($now < 20) {
      $start_date = $start_date->subMonths(1);
    }
    $end_date = $start_date->addMonths(1);

    $departures = $this->departures()->whereHas('departure_reason', function ($query) {
      return $query->where('name', 'PERMISO POR HORAS');
    })->whereBetween('departure', [$start_date, $end_date])->where(function($query) {
        $query->orWhere('approved', true)->orWhere('approved', null);
    })->get();

    $records = $this->departures()->whereHas('departure_reason', function ($query) {
      return $query->where('name', 'REGULARIZACIÓN DE MARCADO');
    })->whereBetween('departure', [$start_date, $end_date])->where(function($query) {
        $query->orWhere('approved', true)->orWhere('approved', null);
    })->count();

    $total_time = DepartureReason::where('name', 'PERMISO POR HORAS')->first()->hours * 60;
    $total_time_records = DepartureReason::where('name', 'REGULARIZACIÓN DE MARCADO')->first()->hours;

    $time_remaining = 0;
    $monthly_reason = DepartureReason::whereName('PERMISO POR HORAS')->first();

    $response = [
      'remaining_records' => 0,
      'time_remaining' => 0,
      'options' => []
    ];

    if (!$monthly_reason->count) {
      return $response;
    }

    if ($departures->count() < $monthly_reason->count) {
      foreach($departures as $d) {
        $departure = Carbon::parse($d->departure);
        $return = Carbon::parse($d->return);
        $time_remaining += $departure->diffInMinutes($return);
      }

      $response = [
        'remaining_records' => $total_time_records - $records,
        'time_remaining' => $total_time - $time_remaining
      ];

      switch ($response['time_remaining']) {
        case 30:
          $response['options'] = [
            ['text' => 'Media hora', 'value' => 0.5]
          ];
          break;
        case 60:
          $response['options'] = [
            ['text' => 'Una hora', 'value' => 1]
          ];
          if ($monthly_reason->count > 2) {
            array_unshift($response['options'], ['text' => 'Media hora', 'value' => 0.5]);
          }
          break;
        case 90:
          if ($monthly_reason->count <= 2) {
            $response['options'] = [
              ['text' => 'Hora y media', 'value' => 1.5]
            ];
            break;
          }
        case 120:
          $response['options'] = [
            ['text' => 'Media hora', 'value' => 0.5],
            ['text' => 'Una hora', 'value' => 1],
            ['text' => 'Hora y media', 'value' => 1.5]
          ];
          break;
        default:
          $response['options'] = [];
      }
    }
    return $response;
  }

  public function annually_remaining_departures($id)
  {
    $reason = DepartureReason::findOrFail($id);
    $total_time = $reason->hours;
    if (!$total_time || $reason->reset != 'annually') return null;

    $start_date = CarbonImmutable::now()->month(1)->startOfMonth();
    $end_date = $start_date->month(12)->endOfMonth();

    $departures = $this->departures()->whereHas('departure_reason', function ($query) use ($id) {
      return $query->whereId($id);
    })->whereBetween('departure', [$start_date, $end_date])->where(function($query) {
        $query->orWhere('approved', true)->orWhere('approved', null);
    })->get();

    $response = [
      'time_remaining' => 0,
      'options' => []
    ];

    if ($departures->count() == 0) {
      $options = [];
      if ($total_time == 4) {
        $options = [
          ['text' => 'Media jornada', 'value' => 4]
        ];
      } elseif ($total_time >= 8) {
        $options = [
          ['text' => 'Media jornada', 'value' => 4],
          ['text' => 'Una jornada', 'value' => 8]
        ];
      }
      $response = [
        'time_remaining' => $total_time,
        'options' => $options
      ];
    } else {
      $time_remaining = 0;

      foreach ($departures as $departure) {
        $start = Carbon::parse($departure->departure);
        $return = Carbon::parse($departure->return);
        $used_time = $start->diffInHours($return);
        if ($used_time > 8) $used_time = 8;
        $time_remaining += $used_time;
      }

      $time_remaining = $total_time - $time_remaining;

      $response = [
        'time_remaining' => $time_remaining
      ];

      if ($time_remaining == 4) {
        $response['options'] = [
          ['text' => 'Media jornada', 'value' => 4]
        ];
      } elseif ($time_remaining < $total_time && $time_remaining > 0) {
        $response['options'] = [
          ['text' => 'Media jornada', 'value' => 4],
          ['text' => 'Una jornada', 'value' => 8]
        ];
      } else {
        $response['options'] = [];
      }
    }
    return $response;
  }

  public function days_non_payable_month($date, $with_contract = false)
  {
    $contracts = collect([]);
    $days = 0;
    $start_month = Carbon::parse($date)->startOfMonth();
    $end_month = Carbon::parse($date)->endOfMonth();
    $requests = $this->departures()->whereHas('departure_reason', function($query) {
      $query->wherePayable(false);
    })->whereApproved(true)->where(function($query) use ($start_month) {
      $query->whereDate('departure', '>=', $start_month->toDateString());
    })->where(function($query) use ($end_month) {
      $query->whereDate('return', '<=', $end_month->toDateString());
    })->orderBy('return', 'DESC')->get();
    foreach ($requests as $request) {
      $departure = Carbon::parse($request->departure);
      $return = Carbon::parse($request->return);
      if ($with_contract) {
        $contract = $this->contracts()->where(function ($query) use ($return) {
          $query->orWhere(function($q) use ($return) {
            $q->whereDate('start_date', '<=', $return->toDateString())->whereDate('end_date', '>=', $return->toDateString())->whereNull('retirement_date');
          })->orWhere(function($q) use ($return) {
            $q->whereDate('start_date', '<=', $return->toDateString())->whereDate('retirement_date', '>=', $return->toDateString())->whereNotNull('retirement_date');
          })->orWhere(function($q) use ($return) {
            $q->whereDate('start_date', '<=', $return->toDateString())->whereNotNull('start_date');
          });
        })->orderBy('end_date', 'DESC')->orderBy('retirement_date', 'DESC')->limit(1)->first();
        $last_contract = $contracts->contains(function($item, $key) use ($contract) {
          return $key == $contract->id;
        });
        if (!$last_contract) {
          $days = 0;
          $contracts->merge([$contract->id => $days]);
        } else {
          $days = $contracts[$contract->id];
        }
      }
      $difference = $return->diffInDays($departure);
      if ($difference == 0) {
        $hours = $return->diffInHours($departure);
        if ($hours <= 4) {
          $days += 0.5;
        } else {
          $days += 1;
        }
      } else {
        if ($departure->lessThan($start_month)) $departure = $start_month;
        if ($return->greaterThan($end_month)) $return = $end_month;
        // Payment with weekend
        $days += 1 + $difference;
      }
      if ($with_contract) {
        $contracts[$contract->id] = $days;
      }
    }
    return $with_contract ? $contracts : $days;
  }

  public function days_non_payable_year($year)
  {
    $days = 0;
    $month = Carbon::create($year, 1, 1)->firstOfYear();
    for ($i = 1; $i <= 12; $i++) {
      $days += $this->days_non_payable_month($month->toDateString());
      $month->addMonth()->startOfMonth();
    }
    return $days;
  }

  public function get_cas()
  {
    return $this->hasMany(CasCertification::class);
  }

  public function GetActiveCasAttribute()
  {
    return $this->get_cas->where('active', true)->first();
  }

  public function GetSeniorityBonusAttribute()
  {
    if($this->active_cas){
      $years = $this->active_cas->years;
    }else{
      $years = 0;
    }
    $bonus = 0;
    $seniorityBonuses = app(SeniorityBonusController::class)->getBonusCalculate();
 
    foreach ($seniorityBonuses as $seniorityBonus) {
      if ($years >= $seniorityBonus->from && $years <= $seniorityBonus->to) {
        $bonus = $seniorityBonus->calculated;
      }
    }
     return $bonus;
  }
  public function GetPreviousSeniorityBonusAttribute()
  {
    if($this->active_cas){
      $years = $this->active_cas->years;
    }else{
      $years = 0;
    }
    $bonus = 0;
    $previousSeniorityBonuses = app(SeniorityBonusController::class)->getPreviousBonusCalculate();
 
    foreach ($previousSeniorityBonuses as $seniorityBonus) {
      if ($years >= $seniorityBonus->from && $years <= $seniorityBonus->to) {
        $bonus = $seniorityBonus->calculated;
      }
    }
     return $bonus;
  }

  public function vacation_queues()
  {
    return $this->hasMany(VacationQueue::class);
  }
  public function accumulated_days($employee_id)
  {
    return VacationQueue::where('employee_id', $employee_id)
      ->where(Carbon::now()->format('Y-m-d'), '<=', 'max_date')
      ->sum('days');
  }
  public function getSumRestDaysAttribute()
  {
    return $this->vacation_queues()
      ->where('max_date', '>=', Carbon::parse(now())->format('Y-m-d'))
      ->sum('rest_days');
  }
  public function getDaysAssignedAttribute()
  {
    return $this->vacation_queues()
      ->where('max_date', '>=', Carbon::parse(now())->format('Y-m-d'))
      ->sum('days');
  }
  public function admission_date_min()
  {
    return Carbon::parse(Employee::min('addmission_date'));
  }

  public function assistant_contracts()
  {
    return $this->hasMany(AssistantContract::class);
  }

  public function last_assistant_contract()
  {
    return $this->assistant_contracts->where('active', true)->first();
  }
}
