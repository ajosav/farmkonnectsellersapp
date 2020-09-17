<?php

namespace App\Listeners;

use App\Model\Order;
use App\Model\Transaction;
use App\LogisticCompanyProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\DeliveryRequestDeclinedByLogisticsCompany;

class HandleDeclinedDeliveryRequest
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

        $logistic_company = LogisticCompanyProfile::where('user_uuid', Auth::user()->uuid)->first();

        Mail::to($order->user->email)->send(new DeliveryRequestDeclinedByLogisticsCompany($delivery, $order, $logistic_company));

        return Transaction::create([
            'uuid' => mt_rand(),
            'txn_ref' => mt_rand(),
            'user_id' => $order->user_id,
            'amount' => $event->delivery->fee,
            'type' => 'Credit',
            'title' => 'Reversal of Delivery Request Payment',
            'narration' => 'Request for Delivery of ' . $order->product->name . ' from ' . $logistic_company->name,
            'status' => 1
        ]);
    }
}