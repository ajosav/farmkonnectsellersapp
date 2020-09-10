<?php

namespace App\Listeners;

use App\Mail\NoticeOfDeclinedOrder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Gate;

class NotifyBuyerOfDeclinedOrder
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

        if (Gate::allows('Commodity Distributor')) {

            $role = "commodityRetailerProfile";
        }

        if (Gate::allows('Commodity Retailer')) {

            $role = "commodityConsumerProfile";
        }


        Mail::to($order->user->$role->email)->send(new NoticeOfDeclinedOrder($order));
    }
}