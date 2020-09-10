<?php

namespace App\Listeners;

use App\Model\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogDeclinedTransaction
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

        if (Gate::allows('Farm Manager')) {

            $role = "FarmManagerProfile";
        }

        if (Gate::allows('Commodity Distributor')) {

            $role = "CommodityDistributorProfile";
        }

        if (Gate::allows('Commodity Retailer')) {

            $role = "CommodityRetailerProfile";
        }


        new Transaction();

        return Transaction::create([
            'uuid' => mt_rand(),
            'txn_ref' => mt_rand(),
            'user_id' => $event->order->user_id,
            'amount'  => $event->order->total_price,
            'type'   => 'Credit',
            'title' => 'Transaction Reversal',
            'narration' => 'Order was declined by  ' . $event->order->product->owner->$role->contact_person,
            'status' => 1
        ]);
    }
}