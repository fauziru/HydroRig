<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    public function category ()
    {
        return $this->belongsTo('App\Models\Category','category_id','id');
    }

    public function childcategory () 
    {
        return $this->hasMany('App\Models\ChildCategory', 'sub_category_id');
    }
}
