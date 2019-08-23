<?php

namespace App;

use App\Helpers\Util;
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
  protected $fillable = ['city_identity_card_id', 'management_entity_id', 'identity_card', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband', 'birth_date', 'city_birth_id', 'account_number', 'country_birth', 'nua_cua', 'gender', 'location', 'zone', 'street', 'address_number', 'phone_number', 'landline_number', 'active'];

  public function fullName($style = "uppercase", $order = "name_first")
  {
    return Util::fullName($this, $style, $order);
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
    if ($this->consultant()) {
      return ConsultantContract::whereEmployeeId($this->id)->where('start_date', '<=', $date)->orderBy('start_date', 'DESC')->first();
    } else {
      return Contract::whereEmployeeId($this->id)->where('start_date', '<=', $date)->orderBy('start_date', 'DESC')->first();
    }
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
    return $this->contracts->count() + $this->consultant_contracts->count();
  }

  public function consultant()
  {
    $contract = $this->last_contract();
    $consultant_contract = $this->last_consultant_contract();
    $type = null;
    if ($contract && $consultant_contract) {
      if (Carbon::parse($contract->start_date)->greaterThan(Carbon::parse($consultant_contract->start_date))) {
        $type = 'eventual';
      } else {
        $type = 'consultant';
      }
    } elseif (!$contract && $consultant_contract) {
      $type = 'consultant';
    } elseif ($contract && !$consultant_contract) {
      $type = 'eventual';
    }
    switch ($type) {
      case 'eventual':
        if ($contract->end_date != null) {
          if ($contract->retirement_date) {
            if (Carbon::parse($contract->retirement_date)->greaterThan(Carbon::now())) {
              return false;
            }
          } else {
            if (Carbon::parse($contract->end_date)->greaterThan(Carbon::now())) {
              return false;
            }
          }
        } else {
          return false;
        }
        return null;
        break;
      case 'consultant':
        if ($consultant_contract->end_date != null) {
          if ($consultant_contract->retirement_date) {
            if (Carbon::parse($consultant_contract->retirement_date)->greaterThan(Carbon::now())) return true;
          } else {
            if (Carbon::parse($consultant_contract->end_date)->greaterThan(Carbon::now())) return true;
          }
        } else {
          return true;
        }
        return null;
        break;
      default:
        return null;
    }
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
    return $this->contracts()->orderBy('start_date', 'DESC')->first();
  }

  public function last_consultant_contract()
  {
    return $this->consultant_contracts()->orderBy('start_date', 'DESC')->first();
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
    })->whereBetween('departure', [$start_date, $end_date])->get();

    $records = $this->departures()->whereHas('departure_reason', function ($query) {
      return $query->where('name', 'REGULARIZACIÓN DE MARCADO');
    })->whereBetween('departure', [$start_date, $end_date])->count();

    $total_time = DepartureReason::where('name', 'PERMISO POR HORAS')->first()->hours * 60;
    $total_time_records = DepartureReason::where('name', 'REGULARIZACIÓN DE MARCADO')->first()->hours;

    $time_remaining = 0;

    if ($departures->count() < 4) {
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
            ['text' => 'Media hora', 'value' => 0.5],
            ['text' => 'Una hora', 'value' => 1]
          ];
          break;
        case 90:
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
    } else {
      $response = [
        'remaining_records' => 0,
        'time_remaining' => 0,
        'options' => []
      ];
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
    })->whereBetween('departure', [$start_date, $end_date])->get();

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
}
