<?php

namespace App\Providers;

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
        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Register WhatsApp Notification Listeners
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvoiceGenerated::class,
            \App\Listeners\SendInvoiceNotification::class,
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvoicePaid::class,
            \App\Listeners\SendPaymentNotification::class,
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\TicketCreated::class,
            \App\Listeners\SendTicketNotification::class,
        );
    }
}
