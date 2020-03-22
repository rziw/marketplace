<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function product()
    {
        $this->belongsTo('App\Models\Product');
    }

    public function shop()
    {
        $this->belongsTo('App\Models\Shop');
    }
}
