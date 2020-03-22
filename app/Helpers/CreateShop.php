<?php


namespace App\Helpers;


use App\Models\Shop;
use App\Models\User;
use App\Notifications\MarketChanged;
use Illuminate\Support\Facades\Mail;

class CreateShop
{
    public function store(User $user)
    {
        Shop::updateOrCreate(['owner_id' => $user->id]);
        Mail::fake();//Mock sending email unless all requirements of sending email are OK
        $message = "You are a seller now and you have your own market in our system!";
        $user->notify(new MarketChanged($message));
    }
}
