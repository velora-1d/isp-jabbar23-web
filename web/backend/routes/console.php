<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::command('billing:generate')
    ->monthlyOn(1, '01:00')
    ->description('Generate monthly invoices');

\Illuminate\Support\Facades\Schedule::job(new \App\Jobs\CheckOverdueInvoicesJob)
    ->dailyAt('02:00')
    ->description('Suspend unpaid customers past grace period');

\Illuminate\Support\Facades\Schedule::job(new \App\Jobs\CheckDueSoonInvoicesJob)
    ->dailyAt('09:00')
    ->description('Send warning for invoices due soon');

