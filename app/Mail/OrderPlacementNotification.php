<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlacementNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $role;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        //
        $this->order = $order;

        if (Gate::allows('Commodity Distributor')) {

            $this->role = "farmManagerProfile";
        }

        if (Gate::allows('Commodity Retailer')) {

            $this->role = "commodityDistributorProfile";
        }

        if (Gate::allows('Commodity Consumer')) {

            $this->role = "commodityRetailerProfile";
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('New Order Placed For Vendor Product')->view('emails.orders.new-order-notification');
    }
}
