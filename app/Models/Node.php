<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Node extends Model
{
    use Uuid, LogsActivity, Blameable;

    protected $table = 'nodes';

    protected $fillable = ['name_node', 'status', 'konektivitas', 'lat', 'lng'];

    protected static $logAttributes = ['name_node', 'konektivitas', 'lat', 'lng'];

    public function sensors()
    {
        return $this->hasMany('App\Models\Sensor');
    }

    public function reads()
    {
        return $this->hasManyThrough('App\Models\Read', 'App\Models\Sensor', 'node_id', 'sensor_id');
    }

    public function getLastRead()
    {
        $data = $this->hasMany('App\Models\Sensor')->where('node_id', $this->id)->get();

        return $data;
    }

    public function resolveRouteBinding($value, $field = NULL)
    {
        return $this->where('uuid', $value)->first() ?? abort(404);
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "Node";
        $activity->description = "activity.logs.node.{$eventName}";
    }
}
