<?php

namespace App\Providers;

use App\User;
use Illuminate\Http\Request;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('app_env') === 'production') {
            if( ! Request::secure())
            {
                return \Redirect::secure(\Request::path());
            }
        }

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        
        User::observe(UserObserver::class);
        view()->composer('*', function($view) {
            $users = User::all();
            $view->with('users', $users);
        });

    }
}
