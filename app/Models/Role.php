<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Role extends Model
{
    //
    use Uuid, LogsActivity, Blameable;
    
    protected $table = 'roles';    

    protected $fillable = ['name_role', 'create', 'read', 'update', 'delete'];

    protected static $logAttributes = [];

    public function resolveRouteBinding($value, $field = NULL)
    {
        return $this->where('uuid', $value)->first() ?? abort(404);
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->log_name = "Role";
        $activity->description = "activity.logs.role.{$eventName}";
    }

    public function user()
    {
        return $this->hasMany('App\User');
    }
}
