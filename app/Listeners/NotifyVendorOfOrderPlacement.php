<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacementNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyVendorOfOrderPlacement
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

        Mail::to($order->product->owner->email)->send(new OrderPlacementNotification($order));
    }
}