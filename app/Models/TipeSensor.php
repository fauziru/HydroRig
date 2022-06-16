<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeSensor extends Model
{
    protected $table = 'tipe_sensor';

    protected $fillable = ['name_tipe_sensor', 'kode'];

    public function sensor()
    {
        return $this->hasMany('App\Models\Sensor', 'kode', 'tipe');
    }
}
