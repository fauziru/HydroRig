<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensor';

    protected $fillable = ['min_nutrisi', 'konektivitas', 'name_sensor'];

    public function readNutrisi()
    {
        return $this->hasMany('App\Models\ReadNutrisi')->orderBy('id', 'desc');
    }
}
