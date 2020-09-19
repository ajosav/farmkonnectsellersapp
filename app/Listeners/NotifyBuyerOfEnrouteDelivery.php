<?php

namespace App\Listeners;

use App\Model\Order;
use App\LogisticCompanyProfile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ProductsEnrouteToDestination;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyBuyerOfEnrouteDelivery
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
        $logistic_company = LogisticCompanyProfile::where('uuid', $delivery->logistic_id)->first();

        Mail::to($order->user->email)->send(new ProductsEnrouteToDestination($delivery, $order, $logistic_company));
    }
}