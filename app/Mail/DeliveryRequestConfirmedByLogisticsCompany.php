<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\LogisticCompanyProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeliveryRequestConfirmedByLogisticsCompany extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $delivery;
    public $logistic_company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($delivery, $order)
    {
        //
        $this->delivery = $delivery;
        $this->order = $order;
        $this->logistic_company = LogisticCompanyProfile::where('user_uuid', Auth::user()->uuid)->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.logistics.delivery-request-confirmation');
    }
}