<?php

namespace App\Providers;

use App\Events\OrderDeclined;
use App\Events\OrderConfirmed;
use App\Listeners\LogWalletCredit;
use Illuminate\Auth\Events\Verified;
use App\Events\WalletCreditValidated;
use Illuminate\Support\Facades\Event;
use App\Listeners\CreateNewUserWallet;
use App\Listeners\LogWalletWithdrawal;
use Illuminate\Auth\Events\Registered;
use App\Events\OrderSuccessfullyPlaced;
use App\Listeners\OnboardVerifiedUsers;
use App\Listeners\LogDeclinedTransaction;
use App\Events\DeliverySuccessfullyRequested;
use App\Listeners\NotifyBuyerOfDeclinedOrder;
use App\Events\SuccessfulUserWalletWithdrawal;
use App\Listeners\NotifyVendorOfOrderPlacement;
use App\Listeners\HandleDeclinedDeliveryRequest;
use App\Listeners\LogDeliveryRequestTransaction;
use App\Listeners\LogSuccessfulOrderTransaction;
use App\Listeners\NotifyBuyerOfOrderConfirmation;
use App\Listeners\ReverseDeclinedOrderTransaction;
use App\Events\DeliveryRequestSuccessfullyConfirmed;
use App\Events\DeliveryRequestDeclinedByLogisticsCompany;
use App\Listeners\NotifyBuyerOfLogisticsCompanyConfirmation;
use App\Listeners\NotifyLogisticCompanyOfNewDeliveryRequest;
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
        ],
        WalletCreditValidated::class => [
            LogWalletCredit::class
        ],
        SuccessfulUserWalletWithdrawal::class => [
            LogWalletWithdrawal::class
        ],
        OrderSuccessfullyPlaced::class => [
            LogSuccessfulOrderTransaction::class,
            NotifyVendorOfOrderPlacement::class,
        ],
        OrderConfirmed::class => [
            NotifyBuyerOfOrderConfirmation::class,
        ],
        OrderDeclined::class => [
            ReverseDeclinedOrderTransaction::class,
            LogDeclinedTransaction::class,
            NotifyBuyerOfDeclinedOrder::class,
        ],
        DeliverySuccessfullyRequested::class => [
            LogDeliveryRequestTransaction::class,
            NotifyLogisticCompanyOfNewDeliveryRequest::class,
        ],
        DeliveryRequestSuccessfullyConfirmed::class => [
            NotifyBuyerOfLogisticsCompanyConfirmation::class,
        ],
        DeliveryRequestDeclinedByLogisticsCompany::class => [
            HandleDeclinedDeliveryRequest::class
        ],
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