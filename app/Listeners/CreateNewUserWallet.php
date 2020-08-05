<?php

namespace App\Listeners;

use App\Model\Wallet;
use Illuminate\Auth\Events\Verified;


class CreateNewUserWallet
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        //
        $wallet = new Wallet();
        $wallet->user_id = $event->user->uuid;
        $wallet->save();
    }
}