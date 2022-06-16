<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Sensor extends Model
{
    use Uuid, LogsActivity, Blameable;

    protected $table = 'sensors';

    protected $fillable = ['name_sensor', 'threshold', 'node_id', 'tipe', 'last_read', 'last_read_time'];

    protected static $logAttributes = ['name_sensor', 'threshold',];

    public function node()
    {
        return $this->belongsTo('App\Models\Node','node_id','id');
    }

    public function tipeSensor()
    {
        return $this->belongsTo('App\Models\TipeSensor', 'tipe', 'kode');
    }

    public function read()
    {
        return $this->hasMany('App\Models\Read')->orderBy('id', 'desc')->take(50);
    }

    public function resolveRouteBinding($value, $field = NULL)
    {
        return $this->where('uuid', $value)->first() ?? abort(404);
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "Sensor";
        $activity->description = "activity.logs.sensor.{$eventName}";
    }
}
