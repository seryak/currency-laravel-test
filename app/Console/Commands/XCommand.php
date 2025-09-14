<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Services\CurrencyConverter;
use App\Enums\CurrencyEnum;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class XCommand extends Command
{
    protected $signature = 'x';

    public function handle()
    {
        $converter = new CurrencyConverter();
        $amount = 100;
        $from = CurrencyEnum::EUR;
        $to = CurrencyEnum::RUB;
        dd($convertedAmount = $converter->convert($amount, $from, $to));

    }
}