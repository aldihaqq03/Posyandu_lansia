<?php

namespace App\Providers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL; // ← pastikan baris ini ada
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        Paginator::useBootstrap();
        Event::listen(Verified::class, function (Verified $event) {
            $event->user?->petugas?->update([
                'status' => 'aktif',
            ]);
        });
    }
}
