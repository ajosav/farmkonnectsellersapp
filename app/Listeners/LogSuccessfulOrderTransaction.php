<?php

namespace App\Listeners;

use App\Model\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LogSuccessfulOrderTransaction
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


        new Transaction();

        return Transaction::create([
            'uuid' => mt_rand(),
            'txn_ref' => mt_rand(),
            'user_id' => Auth::user()->uuid,
            'amount' => $event->order->total_price,
            'type' => 'Debit',
            'title' => 'Payment for Product Order',
            'narration' => 'Order for ' . $event->order->product->name . ' from ' . $event->order->product->owner->$role->contact_person,
            'status' => 1
        ]);
    }
}