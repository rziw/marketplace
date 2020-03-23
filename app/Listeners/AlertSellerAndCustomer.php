<?php

namespace App\Listeners;

use App\Events\ProductFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AlertSellerAndCustomer
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
     * @param  ProductFinished  $event
     * @return void
     */
    public function handle(ProductFinished $event)
    {
        //TODO alert using web socket in future
    }
}
