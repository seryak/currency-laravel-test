<?php

namespace App\Actions\Rates;

use App\Services\CurrencyRateFetcher;
use App\Services\CurrencyRateStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateRates
{
    public function __invoke(Request $request, CurrencyRateFetcher $rateFetcher, CurrencyRateStorage $rateStorage): RedirectResponse
    {
        try {
            $rates = $rateFetcher->fetch();
            $rateStorage->save($rates);

            return redirect()->back()->with('success', 'Курсы валют успешно обновлены');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ошибка при обновлении курсов: ' . $e->getMessage());
        }
    }
}