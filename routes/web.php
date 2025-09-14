<?php

use App\Actions\Convertation\HandleConverter;
use App\Actions\Convertation\ShowConverter;
use App\Actions\Currencies\ShowCurrencies;
use App\Actions\Currencies\ToggleCurrency;
use App\Actions\Rates\UpdateRates;
use App\Actions\Rates\ShowRates;
use Illuminate\Support\Facades\Route;

Route::prefix('rates')->name('rates.')->group(function () {
    Route::get('/', ShowRates::class)->name('show');
    Route::post('/update', UpdateRates::class)->name('update');
});

Route::prefix('currencies')->name('currencies.')->group(function () {
    Route::get('/', ShowCurrencies::class)->name('show');
    Route::get('/toggle/{id}', ToggleCurrency::class)->name('toggle');
});

Route::prefix('converter')->name('converter.')->group(function () {
    Route::get('/', ShowConverter::class)->name('show');
    Route::post('/', HandleConverter::class)->name('handle');
});