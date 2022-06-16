<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Traits\Blameable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Layout extends Model
{
    use Uuid, LogsActivity, Blameable;

    protected $table = 'layouts';

    protected $fillable = ['name_layout', 'file_name', 'name_layout'];

    protected static $logAttributes = ['name_layout', 'file_name', 'name_layout'];

    public function settings()
    {
        return $this->hasOne('App\Models\Setting');
    }

    public function resolveRouteBinding($value, $field = NULL)
    {
        return $this->where('uuid', $value)->first() ?? abort(404);
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "Layout";
        $activity->description = "activity.logs.layout.{$eventName}";
    }
}
