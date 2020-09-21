<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryConfirmedByBuyer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $delivery;

    public function __construct($delivery)
    {
        //
        $this->delivery = $delivery;
    }
}
