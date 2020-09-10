<?php

namespace App\Events;

use Illuminate\Support\Facades\Gate;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OrderConfirmed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $role;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        //
        $this->order = $order;

        if (Gate::allows('Farm Manager')) {

            $this->role = "commodityDistributorProfile";
        }

        if (Gate::allows('Commodity Distributor')) {

            $this->role = "commodityRetailerProfile";
        }

        if (Gate::allows('Commodity Retailer')) {

            $this->role = "commodityConsumerProfile";
        }
    }
}