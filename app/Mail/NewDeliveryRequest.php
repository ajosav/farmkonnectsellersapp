<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewDeliveryRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $delivery;
    public $role;
    public $logistic_company;
    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($delivery, $role, $logistic_company, $order)
    {
        //
        $this->delivery = $delivery;
        $this->role = $role;
        $this->logistic_company = $logistic_company;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Request for Delivery Service')->view('emails.logistics.new-delivery-request');
    }
}