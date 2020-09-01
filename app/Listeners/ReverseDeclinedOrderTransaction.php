<?php

namespace App\Listeners;

use App\Model\Order;
use App\Model\Wallet;

class ReverseDeclinedOrderTransaction
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

        $wallet = Wallet::where('user_id', $order->user_id)->first();

        return Wallet::where('user_id', $order->user_id)->update([
            'balance' => $wallet->balance + $order->total_price
        ]);
    }
}