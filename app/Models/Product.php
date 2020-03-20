<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function shops()
    {
        return $this->belongsToMany('App\Models\Shop')->withPivot(['count', 'price', 'discount', 'color',
            'has_guarantee', 'guarantee_description']);
    }
}
