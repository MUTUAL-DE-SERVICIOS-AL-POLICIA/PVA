<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VacationTime extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = [
        'departure_id', 
        'day',
        'date', 
        'period', 
        'vacation_queue_id'];
    protected $table ='vacation_time';
    public function departure()
    {
        return $this->belongsTo(Departure::class);
    }
    public function vacation_queue()
    {
        return $this->belongsTo(VacationQueue::class);
    }
}

