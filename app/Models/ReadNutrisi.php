<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadNutrisi extends Model
{
    protected $table = 'read_nutrisi';

    protected $fillable = ['sensor_id', 'read_nutrisi'];

    public function sensor()
    {
        return $this->belongsTo('App/Models/Sensor');
    }
}
