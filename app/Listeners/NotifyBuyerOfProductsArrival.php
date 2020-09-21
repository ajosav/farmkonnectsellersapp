<?php

namespace App\Listeners;

use App\Model\Order;
use App\LogisticCompanyProfile;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArrivalOfOrderedProducts;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyBuyerOfProductsArrival
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

        Mail::to($order->user->email)->send(new ArrivalOfOrderedProducts($delivery, $order, $logistic_company));
    }
}
