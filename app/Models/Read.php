<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Read extends Model
{
    protected $table = 'reads';

    protected $fillable = ['sensor_id', 'read'];

    public function sensor()
    {
        return $this->belongsTo('App/Models/Sensor');
    }
}
