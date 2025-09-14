<?php

namespace App\Actions\Currencies;

use App\Models\Currency;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ShowCurrencies
{
    public function __invoke(Request $request): View|Application|Factory|\Illuminate\View\View
    {
        $currencies = Currency::orderBy('code')->get();

        return view('admin.currencies.index', compact('currencies'));
    }
}