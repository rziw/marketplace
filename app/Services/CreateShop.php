<?php


namespace App\Services;


use App\Models\Shop;
use App\Models\User;
use App\Notifications\MarketAdded;
use Illuminate\Support\Facades\Mail;

class CreateShop
{
    public function store(User $user)
    {
        Shop::updateOrCreate(['owner_id' => $user->id]);
        Mail::fake();//Mock sending email unless all requirements of sending email are OK
        $user->notify(new MarketAdded());
    }
}
