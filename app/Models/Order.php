<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }
}
