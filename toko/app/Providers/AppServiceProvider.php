<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\BaranginObserver;
use App\Observers\AccjurnalkhususObserver;
use App\Models\Accjurnalkhusus;
use App\Models\Barangin;

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
        Barangin::observe(BaranginObserver::class);
        Accjurnalkhusus::observe(AccjurnalkhususObserver::class);
    }
}
