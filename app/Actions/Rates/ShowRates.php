<?php

namespace App\Actions\Rates;

use App\Models\ExchangeRate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ShowRates
{
    public function __invoke(Request $request): View|Application|Factory|\Illuminate\View\View
    {
        $rates = ExchangeRate::with('currency')->orderBy('date', 'desc')->paginate('50');

        return view('currencies.index', compact('rates'));
    }
}