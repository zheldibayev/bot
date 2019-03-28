<?php

namespace App;

use Kalnoy\Nestedset\NodeTrait;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use NodeTrait;

    protected $guarded = [];


    public function products()
    {
        return $this->hasMany('App\Product');
    }

}
