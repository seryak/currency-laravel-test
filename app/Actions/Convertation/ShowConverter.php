<?php

namespace App\Actions\Convertation;

use App\Models\Currency;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ShowConverter
{
    public function __invoke(Request $request): View|Application|Factory|\Illuminate\View\View
    {
        $currencies = Currency::where('is_active', true)->orderBy('code')->get();
        return view('currencies.convert', compact('currencies'));
    }
}