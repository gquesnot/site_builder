<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Schema;
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
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        Model::shouldBeStrict();
        Schema::enableForeignKeyConstraints();
        Filament::registerScripts([app(Vite::class)('resources/filament/filament-turbo.js')]);
        Filament::registerScripts([app(Vite::class)('resources/filament/filament-stimulus.js')]);
    }
}
