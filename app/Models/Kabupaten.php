<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';

    public function kecamatan () {
        return $this->hasMany('App\Models\Kecamatan', 'kabupaten_id');
    }

    public function provinsi ()
    {
        return $this->belongsTo('App\Models\Provinsi','provinsi_id','id');
    }
}
