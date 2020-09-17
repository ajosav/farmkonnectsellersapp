<?php

namespace App\Listeners;

use App\Model\Order;
use App\LogisticCompanyProfile;
use App\Mail\NewDeliveryRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyLogisticCompanyOfNewDeliveryRequest
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

        if (Gate::allows('Commodity Distributor')) {

            $role = "commodityDistributorProfile";
        }

        if (Gate::allows('Commodity Retailer')) {

            $role = "commodityRetailerProfile";
        }

        if (Gate::allows('Commodity Consumer')) {

            $role = "commodityConsumerProfile";
        }

        $logistic_company = LogisticCompanyProfile::where('uuid', $event->delivery->logistic_id)->first();
        $order = Order::where('uuid', $event->delivery->order_id)->first();

        Mail::to($logistic_company->email)->send(new NewDeliveryRequest($event->delivery, $role, $logistic_company, $order));
    }
}