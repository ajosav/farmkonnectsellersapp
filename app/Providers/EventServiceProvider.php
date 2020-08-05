<?php

namespace App\Providers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use App\Listeners\CreateNewUserWallet;
use Illuminate\Auth\Events\Registered;
use App\Listeners\OnboardVerifiedUsers;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Verified::class => [
            OnboardVerifiedUsers::class,
            CreateNewUserWallet::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}