<?php

namespace App\Listeners;

use App\Model\Order;
use App\Model\Transaction;
use App\LogisticCompanyProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogDeliveryRequestTransaction
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

            $role = "farmManagerProfile";
        }

        if (Gate::allows('Commodity Retailer')) {

            $role = "commodityDistributorProfile";
        }

        if (Gate::allows('Commodity Consumer')) {

            $role = "commodityRetailerProfile";
        }

        $order = Order::where('uuid', $event->delivery->order_id)->first();

        $logistic_company = LogisticCompanyProfile::where('uuid', $event->delivery->logistic_id)->first();

        new Transaction();

        return Transaction::create([
            'uuid' => mt_rand(),
            'txn_ref' => mt_rand(),
            'user_id' => Auth::user()->uuid,
            'amount' => $event->delivery->fee,
            'type' => 'Debit',
            'title' => 'Payment for Delivery Request',
            'narration' => 'Request for Delivery of ' . $order->product->name . ' from ' . $logistic_company->name,
            'status' => 1
        ]);
    }
}