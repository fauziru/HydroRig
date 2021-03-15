<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';

    public function kelurahan () {
        return $this->hasMany('App\Models\Kelurahan', 'kecamatan_id');
    }

    public function kabupaten ()
    {
        return $this->belongsTo('App\Models\Kabupaten','kabupaten_id','id');
    }
}
