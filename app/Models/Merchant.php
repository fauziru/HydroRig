<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use Uuid;
    protected $table = "merchants";
}
