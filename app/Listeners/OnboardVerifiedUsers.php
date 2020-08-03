<?php

namespace App\Listeners;

use App\Notifications\Onboard;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OnboardVerifiedUsers implements ShouldQueue
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
     * @param  Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $event->user->notify(new Onboard($event->user));;
    }
}
