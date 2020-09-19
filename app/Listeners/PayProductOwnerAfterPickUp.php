<?php

namespace App\Listeners;

use App\Model\Order;
use App\Model\Transaction;
use App\LogisticCompanyProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyBuyerOfOrderPickup;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PayProductOwnerAfterPickUp
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
        $product = $order->product;

        $pay_owner = DB::transaction(function () use ($delivery, $order, $product) {
            $wallet = new WalletController();
            $credit_wallet = $wallet->credit_wallet($order->total_price, $product->owner->uuid);

            if ($credit_wallet == false) {
                # code...
                throw new ModelNotFoundException("Error Crediting Seller");
            }

            $transaction = new Transaction();

            return Transaction::create([
                'uuid' => mt_rand(),
                'txn_ref' => mt_rand(),
                'user_id' => $product->owner->uuid,
                'amount' => $order->total_price,
                'type' => 'Credit',
                'title' => 'Sale of Product',
                'narration' => 'Sale of ' . $order->product->name . ' to ' . $order->user->name,
                'status' => 1
            ]);
        });

        if ($pay_owner != false) {
            # code...

            $logistic_company = LogisticCompanyProfile::where('uuid', $delivery->logistic_id)->first();

            Mail::to($order->user->email)->send(new NotifyBuyerOfOrderPickup($delivery, $order, $logistic_company));
        }
    }
}