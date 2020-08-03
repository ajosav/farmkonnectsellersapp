<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    private $relations;
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function __construct()
    {
        return $this->relations = [
            'farmManagerProfile',
            'logisticCompanyProfile',
            'commodityConsumerProfile',
            'commodityDistributorProfile',
            'commodityRetailerProfile'
        ];
    }

    public function created(User $user)
    {
        $user_position = $user->positionName->name;
        $user->givePermissionTo($user_position);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        foreach ($this->relations as $relation) {
            $user->$relation()->delete();
        }
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        foreach ($this->relations as $relation) {
            $user->$relation()->withTrashed()->restore();
        }
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
