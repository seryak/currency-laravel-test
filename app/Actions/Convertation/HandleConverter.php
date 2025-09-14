<?php

namespace App\Actions\Convertation;

use App\Enums\CurrencyEnum;
use App\Models\Currency;
use App\Services\CurrencyConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use App\Http\Requests\ConvertCurrencyRequest;

class HandleConverter
{
    public function __invoke(ConvertCurrencyRequest $request): View|Application|Factory|\Illuminate\View\View
    {
        $converter = app(CurrencyConverter::class);
        $currencies = Currency::where('is_active', true)->orderBy('code')->get();
        $result = null;
        $error = null;

        try {
            $result = $converter->convert($request->amount, CurrencyEnum::tryFrom($request->from), CurrencyEnum::tryFrom($request->to));
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return view('currencies.convert', compact('currencies', 'result', 'error', 'request'));
    }
}