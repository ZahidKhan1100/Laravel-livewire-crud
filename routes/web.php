<?php

use App\Livewire\Checkout\Checkout;
use App\Livewire\Orders\Orders;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Support\Facades\Route;
use Laravel\Pail\ValueObjects\Origin\Console;
use MuhammadZahid\UniversalDate\Facades\UniversalDate;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/orders',Orders::class)->name('orders');


Route::get('/test-dates', function () {
    $date = '2024-12-25';
    return [
        'now' => UniversalDate::make()->toHuman(),
        'America' => UniversalDate::make('2025-01-25', 'America/New_York')->toHuman(),
        'in_1_minute' => UniversalDate::make('+1 minute')->toTimeAgo(),
        'in_30_minutes' => UniversalDate::make('+30 minutes')->toTimeAgo(),
        'in_45_minutes' => UniversalDate::make('+45 minutes')->toTimeAgo(),
        'in_1_hour' => UniversalDate::make('+1 hour')->toTimeAgo(),
        'in_2_hours' => UniversalDate::make('+2 hours')->toTimeAgo(),
        'in_1_day' => UniversalDate::make('+1 day')->toTimeAgo(),
        'in_1_month' => UniversalDate::make('+1 month')->toTimeAgo(),
        'in_1_year' => UniversalDate::make('+1 year')->toTimeAgo(),
        'time_ago' => UniversalDate::make($date)->toTimeAgo(),
    ];
});

Route::get('/pipeline', function () {
    $comment = 'Hello World :-)';
    Pipeline::send($comment)
        ->through([
            function ($comment, $next) {
                $comment = trim($comment);
                return $next($comment);
            },
            function ($comment, $next) {
                $comment = str_replace(':-)', '😀', $comment);
                return $next($comment);
            },
        ])
        ->then(function ($comment) {
            return $comment;
        });
});
