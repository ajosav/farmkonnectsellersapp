<?php

namespace App\Listeners;

use App\Model\Order;
use App\Model\Transaction;
use App\LogisticCompanyProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductDeliveryConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreditLogisticCompanyWallet
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

        $wallet = new WalletController();

        $credit_wallet = DB::transaction(function () use ($delivery, $wallet, $logistic_company) {

            $pay_logistics = $wallet->credit_wallet($delivery->fee, $logistic_company->user_uuid);

            if ($pay_logistics == false) {
                # code...

                throw new ModelNotFoundException("Error Paying Logistics Company");
            }

            return true;
        });


        if ($credit_wallet) {
            # code...

            Mail::to($logistic_company->email)->send(new ProductDeliveryConfirmed($delivery, $order, $logistic_company));

            new Transaction();

            return Transaction::create([
                'uuid' => mt_rand(),
                'txn_ref' => mt_rand(),
                'user_id' => $delivery->logistic_id,
                'amount' => $delivery->fee,
                'type' => 'Credit',
                'title' => 'Product Delivery',
                'narration' => 'Delivery of ' . $order->product->name . ' to ' . $order->user->name,
                'status' => 1
            ]);
        }
    }
}