<?php

namespace App\Listeners;

use App\Model\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\DeliveryRequestConfirmedByLogisticsCompany;

class NotifyBuyerOfLogisticsCompanyConfirmation
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        $delivery = $event->delivery;

        $order = Order::where('uuid', $delivery->order_id)->first();

        Mail::to($order->user->email)->send(new DeliveryRequestConfirmedByLogisticsCompany($delivery, $order));
    }
}