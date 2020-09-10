<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlacedConfirmedByVendor extends Mailable
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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.orders.order-confirmed-notification');
    }
}