<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function shops()
    {
        return $this->belongsToMany('App\Models\Shop', 'product_shop')->withPivot(['product_id', 'shop_id', 'count',
            'price', 'discount', 'color', 'has_guarantee', 'guarantee_description', 'status', 'extra_description']);
    }

}
