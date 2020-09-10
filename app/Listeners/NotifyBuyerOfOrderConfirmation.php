<?php

namespace App\Listeners;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\OrderPlacedConfirmedByVendor;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyBuyerOfOrderConfirmation
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
        $order = $event->order;

        if (Gate::allows('Farm Manager')) {

            $role = "commodityDistributorProfile";
        }

        if (Gate::allows('Commodity Distirbutor')) {

            $role = "commodityRetailerProfile";
        }

        if (Gate::allows('Commodity Retailer')) {

            $role = "commodityConsumerProfile";
        }


        Mail::to($order->user->$role->email)->send(new OrderPlacedConfirmedByVendor($order));
    }
}