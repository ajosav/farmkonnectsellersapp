<?php

namespace App\Listeners;

use App\Model\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogWalletCredit
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

        $log = [
            'transaction_id' => $event->transaction->data->id,
            'txn_ref' => $event->transaction->data->tx_ref,
            'reason' => 'The amount debited from customer was ' . $event->transaction->data->amount
        ];

        $log = (object) $log;

        Log::channel('transactions')->info(json_encode($log));

        new Transaction();

        return Transaction::create([
            'uuid' => $event->transaction->data->id,
            'txn_ref' => $event->transaction->data->tx_ref,
            'user_id' => Auth::user()->uuid,
            'amount'  => $event->transaction->data->amount,
            'type'   => 'Credit',
            'title' => 'Wallet Credit',
            'narration' => 'Deposit into wallet via ' . $event->transaction->data->card->type,
            'status' => 1
        ]);
    }
}