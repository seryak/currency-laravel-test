<?php

namespace App\Actions\Currencies;

use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ToggleCurrency
{
    public function __invoke(Request $request, $id): RedirectResponse
    {
        $currency = Currency::findOrFail($id);
        $currency->is_active = !$currency->is_active;
        $currency->save();

        return redirect()->back()->with('success', 'Статус валюты обновлен');
    }
}