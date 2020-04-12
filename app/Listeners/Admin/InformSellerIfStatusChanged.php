<?php

namespace App\Listeners\Admin;

use App\Events\Admin\ProductUpdated;
use App\Notifications\ProductChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class InformSellerIfStatusChanged
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductUpdated  $event
     * @return void
     */
    public function handle(ProductUpdated $event)
    {
        if(isset($event->request->status) && $event->product->pivot->status != $event->request->status) {
            Mail::fake();//Mock sending email unless all requirements of sending email are OK
            $message = "status of product updated to $event->request->status.";
            $event->shop->user->notify(new ProductChanged($message));
        }
    }
}
