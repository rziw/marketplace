<?php


namespace App\Helpers;


class OrderStatusChanger
{
    private $new_status;
    private $previous_status;
    private $order;
    private $success_msg;
    private $error_msg;

    public function instantiateFields($new_status, $order)
    {
        $this->new_status = $new_status;
        $this->previous_status = $order->status;
        $this->order = $order;
        $this->success_msg = "You have successfully updated the status of order to %s";
        $this->error_msg = "Can not change the status from %s to %s";
    }

    public function checkAndUpdateStatus()
    {
        $function_name = $this->new_status;

        if($this->new_status == $this->previous_status) {
            return "Already is ".$this->new_status;
        } else {
            return $this->$function_name();
        }
    }

    //TODO need notify user in each steps
    public function accepted()
    {
        if ($this->previous_status == 'waiting' || $this->previous_status == 'rejected') {
            $this->order->update(['status' => $this->new_status]);
            return sprintf($this->success_msg, $this->new_status);
        } else {
            return sprintf($this->error_msg, $this->previous_status, $this->new_status);
        }
    }

    public function shipped()
    {
        if ($this->previous_status == 'accepted') {
            $this->order->update(['status' => $this->new_status]);
            return sprintf($this->success_msg, $this->new_status);
        } else {
            return sprintf($this->error_msg, $this->previous_status, $this->new_status);
        }
    }

    public function rejected()
    {
        if ($this->previous_status == 'accepted' || $this->previous_status == 'paid') {
            //TODO needs event listener to pay the money back
            $this->order->update(['status' => $this->new_status]);
            return sprintf($this->success_msg, $this->new_status);
        } else {
            return sprintf($this->error_msg, $this->previous_status, $this->new_status);
        }
    }

    public function returned()
    {
        if ($this->previous_status == 'delivered') {
            $this->order->update(['status' => $this->new_status]);
            return sprintf($this->success_msg, $this->new_status);
        } else {
            return sprintf($this->error_msg, $this->previous_status, $this->new_status);
        }
    }

    public function delivered()
    {
        if ($this->previous_status == 'shipped') {
            $this->order->update(['status' => $this->new_status]);
            return sprintf($this->success_msg, $this->new_status);
        } else {
            return sprintf($this->error_msg, $this->previous_status, $this->new_status);
        }
    }
}
