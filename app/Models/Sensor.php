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

    protected $fillable = ['name_sensor', 'threshold', 'node_id'];

    public function read()
    {
        return $this->hasMany('App\Models\Read')->orderBy('id', 'desc');
    }
}
