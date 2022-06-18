<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Traits\Blameable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Setting extends Model
{
    use LogsActivity, Blameable;

    protected $table = 'settings';

    protected static $logAttributes = ['registrasi_key', 'api_key', 'layout_id', 'is_maintenance'];

    public function layout()
    {
        return $this->belongsTo('App\Models\Layout','layout_id','id');
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "Setting";
        $activity->description = "activity.logs.setting.{$eventName}";
    }
}
