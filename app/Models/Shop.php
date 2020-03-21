<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'owner_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_shop')->withPivot(['product_id', 'shop_id', 'count',
            'price', 'discount', 'color', 'has_guarantee', 'guarantee_description', 'status', 'extra_description']);
    }
}
