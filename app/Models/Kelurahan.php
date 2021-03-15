<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'kelurahan';

    public function kecamatan ()
    {
        return $this->belongsTo('App\Models\Kecamatan','kecamatan_id','id');
    }

    public function user()
    {
        return $this->hasMany('App\User', 'kelurahan_id');
    }
}
