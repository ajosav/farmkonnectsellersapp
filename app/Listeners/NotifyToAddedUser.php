<?php

namespace App\Listeners;

use App\Mail\ResetAccountPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyToAddedUser
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
        Mail::to($event->user->email)->send(new ResetAccountPassword($event->user));
    }
}
