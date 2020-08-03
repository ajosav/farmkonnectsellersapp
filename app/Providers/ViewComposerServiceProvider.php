<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            ['pages.user.profile'],
            'App\Http\ViewComposers\TestComposer'
        );
    }
}
