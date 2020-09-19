<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyBuyerOfOrderPickup extends Mailable
{
    use Queueable, SerializesModels;

    public $delivery;
    public $order;
    public $logistic_company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($delivery, $order, $logistic_company)
    {
        //
        $this->delivery = $delivery;
        $this->order = $order;
        $this->logistic_company = $logistic_company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order Items Picked Up By Logistics Company')->view('emails.logistics.order-pickup-notification');
    }
}