<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    protected $table = 'child_categories';

    public function subcategory ()
    {
        return $this->belongsTo('App\Models\SubCategory','sub_category_id','id');
    }
}
