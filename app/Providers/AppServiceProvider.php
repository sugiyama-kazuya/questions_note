<?php

namespace App\Providers;

use App\ExerciseBook;
use App\Observers\ExerciseBookObserver;
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
        ExerciseBook::observe(ExerciseBookObserver::class);
    }
}
