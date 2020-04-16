<?php


namespace App\Helpers;


class OrderStatusChanger
{
    //TODO need notify user in each steps

    public function accepted($new_status, $order)
    {
        if($new_status == $order->status) {
            return "already accepted";
        } elseif ($order->status == 'waiting' || $order->status == 'rejected') {
            $order->update(['status' => $new_status]);
        }
    }

    public function shipped($new_status, $order)
    {
        if($new_status == $order->status) {
            return "already shipped";
        } elseif ($order->status == 'accepted') {
            $order->update(['status' => $new_status]);
        }
    }

    public function rejected($new_status, $order)
    {
        if($new_status == $order->status) {
            return "already rejected";
        } elseif ($order->status == 'accepted' || $order->status == 'paid') {
            $order->update(['status' => $new_status]);
            //TODO needs event listener to pay the money back
        }
    }

    public function returned($new_status, $order)
    {
        if($new_status == $order->status) {
            return "already returned";
        } elseif ($order->status == 'delivered') {
            $order->update(['status' => $new_status]);
        }
    }

    public function delivered($new_status, $order)
    {
        if($new_status == $order->status) {
            return "already delivered";
        } elseif ($order->status == 'shipped') {
            $order->update(['status' => $new_status]);
        }
    }
}
