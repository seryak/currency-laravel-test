<?php

namespace App\Services;

use App\DTO\CurrencyRateDto;
use App\DTO\CurrencyRateDtoCollection;
use App\Enums\CurrencyEnum;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreeCurrencyApiFetcher implements CurrencyRateFetcher
{
    private string $apiKey;
    private string $baseUrl = 'https://api.freecurrencyapi.com/v1';

    public function __construct(private readonly CurrencyEnum $baseCurrency)
    {
        $this->apiKey = config('currency.api_key');
        if (!$this->apiKey) {
            throw new \RuntimeException('Currency API key not configured');
        }
    }

    public function fetch(?Carbon $date = null): CurrencyRateDtoCollection
    {
        $date = $date ?? now();
        if ($date->isFuture()) {
            throw new \InvalidArgumentException('Date cannot be in the future');
        }

        $endpoint = $date->isToday() ? 'latest' : 'historical';
        $params = [
            'apikey' => $this->apiKey,
            'base_currency' => $this->baseCurrency->name,
            'date' => $date->toDateString()
//            'currencies' => '*' // Get all available currencies
        ];

        $response = Http::timeout(30)->get("{$this->baseUrl}/{$endpoint}", $params);

        if (!$response->successful()) {
            Log::error('Failed to fetch currency rates: ' . $response->body());
            throw new \Exception("API request failed: {$response->status()}");
        }

        $data = $endpoint === 'latest' ? $response->json()['data'] : $response->json()['data'][$date->toDateString()];
        $collection = new CurrencyRateDtoCollection();
        $availableCurrencies = Currency::where('is_active', 1)->pluck('code')->toArray();

        foreach ($data as $currencyCode => $rate) {
            if ( ($currencyCode === $this->baseCurrency->name) || !in_array($currencyCode, $availableCurrencies, true) ) {
                continue;
            }

            $dto = new CurrencyRateDto(
                baseCurrencyCode: $this->baseCurrency,
                currencyCode: CurrencyEnum::tryFrom($currencyCode) ?? CurrencyEnum::USD,
                rate: (float) $rate,
                date: $date
            );
            $collection->add($dto);
        }

        return $collection;
    }
}