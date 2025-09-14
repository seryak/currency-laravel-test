<?php

namespace Tests\Unit\Services;

use App\DTO\CurrencyRateDtoCollection;
use App\Enums\CurrencyEnum;
use App\Models\Currency;
use App\Services\FreeCurrencyApiFetcher;
use Carbon\Carbon;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FreeCurrencyApiFetcherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CurrencySeeder::class);
        Config::set('currency.api_key', 'fake_api_key');
    }

    public function testFetchLatestSuccess(): void
    {
        $baseCurrency = CurrencyEnum::USD;
        $fetcher = new FreeCurrencyApiFetcher($baseCurrency);
        $date = Carbon::now();

        $mockData = [
            "AUD" => 100.5030202292,
            "BGN" => 1.6642801979,
            "BRL" => 5.3440008359,
            "CAD" => 1.3834802074,
            "CHF" => 0.7957401087,
            "CNY" => 7.1204208165,
            "CZK" => 20.7090522811,
            "DKK" => 6.3576508163,
            "EUR" => 0.851430155,
            "GBP" => 0.7372101198,
            "HKD" => 7.7754611158,
            "HRK" => 6.2408708123,
            "HUF" => 332.737483205,
            "IDR" => 16362.593907699,
            "ILS" => 3.3329006476,
            "INR" => 88.185433324,
            "ISK" => 121.8665024125,
            "JPY" => 147.6025996513,
            "KRW" => 1390.7083229143,
            "MXN" => 18.4078419767,
            "MYR" => 4.2017005597,
            "NOK" => 9.8529311087,
            "NZD" => 1.6779902408,
            "PHP" => 57.0984082861,
            "PLN" => 3.6219003836,
            "RON" => 4.3138106571,
            "RUB" => 83.0795036691,
            "SEK" => 9.3121117627,
            "SGD" => 1.2807302036,
            "THB" => 31.6298348176,
            "TRY" => 41.3316362243,
            "ZAR" => 17.3533326435,
        ];

        Http::fake([
            'api.freecurrencyapi.com/v1/*' => Http::response(json_encode(['data' => $mockData]), 200),
        ]);

        $collection = $fetcher->fetch($date);

        $this->assertInstanceOf(CurrencyRateDtoCollection::class, $collection);

        $this->assertCount(32, $collection->all()); // Все валюты кроме USD

        $eurDto = $collection->get(CurrencyEnum::EUR);
        $this->assertEquals(0.851430155, $eurDto->rate);
        $this->assertEquals(CurrencyEnum::USD->name, $eurDto->baseCurrencyCode);
        $this->assertEquals(CurrencyEnum::EUR->name, $eurDto->currencyCode);
        $this->assertEquals($date->toDateString(), $eurDto->date->toDateString());

        $gbpDto = $collection->get(CurrencyEnum::GBP);
        $this->assertEquals(0.7372101198, $gbpDto->rate);

        $rubDto = $collection->get(CurrencyEnum::RUB);
        $this->assertEquals(83.0795036691, $rubDto->rate);
    }

    public function testFetchWithSomeUnavailableCurrencies(): void
    {
        $baseCurrency = CurrencyEnum::USD;
        $fetcher = new FreeCurrencyApiFetcher($baseCurrency);

        $mockData = [
            "AUD" => 1.5030202292,
            "BGN" => 1.6642801979,
            "BRL" => 5.3440008359,
            "CAD" => 1.3834802074,
            "CHF" => 0.7957401087,
            "CNY" => 7.1204208165,
            "CZK" => 20.7090522811,
            "DKK" => 6.3576508163,
            "EUR" => 0.851430155,
            "GBP" => 0.7372101198,
            "HKD" => 7.7754611158,
            "HRK" => 6.2408708123,
            "HUF" => 332.737483205,
            "IDR" => 16362.593907699,
            "ILS" => 3.3329006476,
            "INR" => 88.185433324,
            "ISK" => 121.8665024125,
            "JPY" => 147.6025996513,
            "KRW" => 1390.7083229143,
            "MXN" => 18.4078419767,
            "MYR" => 4.2017005597,
            "NOK" => 9.8529311087,
            "NZD" => 1.6779902408,
            "PHP" => 57.0984082861,
            "PLN" => 3.6219003836,
            "RON" => 4.3138106571,
            "RUB" => 83.0795036691,
            "SEK" => 9.3121117627,
            "SGD" => 1.2807302036,
            "THB" => 31.6298348176,
            "TRY" => 41.3316362243,
            "ZAR" => 17.3533326435,
        ];

        Http::fake([
            'api.freecurrencyapi.com/v1/*' => Http::response(json_encode(['data' => $mockData]), 200),
        ]);

        Currency::where('code', CurrencyEnum::EUR)->update(['is_active' => false]);
        Currency::where('code', CurrencyEnum::GBP)->update(['is_active' => false]);

        $collection = $fetcher->fetch(Carbon::now());
        $this->assertCount(30, $collection->all());
    }

    public function testFetchHistoricalSuccess(): void
    {
        $baseCurrency = CurrencyEnum::USD;
        $fetcher = new FreeCurrencyApiFetcher($baseCurrency);
        $date = Carbon::parse('2024-01-01');

        $mockData = [
            "AUD" => 1.5030202292,
            "BGN" => 1.6642801979,
            "BRL" => 5.3440008359,
            "CAD" => 1.3834802074,
            "CHF" => 0.7957401087,
            "CNY" => 7.1204208165,
            "CZK" => 20.7090522811,
            "DKK" => 6.3576508163,
            "EUR" => 0.851430155,
            "GBP" => 0.7372101198,
            "HKD" => 7.7754611158,
            "HRK" => 6.2408708123,
            "HUF" => 332.737483205,
            "IDR" => 16362.593907699,
            "ILS" => 3.3329006476,
            "INR" => 88.185433324,
            "ISK" => 121.8665024125,
            "JPY" => 147.6025996513,
            "KRW" => 1390.7083229143,
            "MXN" => 18.4078419767,
            "MYR" => 4.2017005597,
            "NOK" => 9.8529311087,
            "NZD" => 1.6779902408,
            "PHP" => 57.0984082861,
            "PLN" => 3.6219003836,
            "RON" => 4.3138106571,
            "RUB" => 83.0795036691,
            "SEK" => 9.3121117627,
            "SGD" => 1.2807302036,
            "THB" => 31.6298348176,
            "TRY" => 41.3316362243,
            "ZAR" => 17.3533326435,
        ];

        $historicalResponse = [
            'data' => [
                '2024-01-01' => $mockData,
            ],
        ];

        Http::fake([
            'api.freecurrencyapi.com/v1/*' => Http::response(json_encode($historicalResponse), 200),
        ]);

        $collection = $fetcher->fetch($date);

        $this->assertInstanceOf(CurrencyRateDtoCollection::class, $collection);
        $this->assertCount(32, $collection->all());

        $eurDto = $collection->get(CurrencyEnum::EUR);
        $this->assertEquals(0.851430155, $eurDto->rate);
        $this->assertEquals(CurrencyEnum::USD->name, $eurDto->baseCurrencyCode);
        $this->assertEquals(CurrencyEnum::EUR->name, $eurDto->currencyCode);
        $this->assertEquals('2024-01-01', $eurDto->date->toDateString());

        $rubDto = $collection->get(CurrencyEnum::RUB);
        $this->assertEquals(83.0795036691, $rubDto->rate);
    }

    public function testFetchIfDAteIsFuture(): void
    {
        $baseCurrency = CurrencyEnum::USD;
        $fetcher = new FreeCurrencyApiFetcher($baseCurrency);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Date cannot be in the future');

        $fetcher->fetch(Carbon::tomorrow());
    }

    public function testFetchIfResponseNotSuccessful(): void
    {
        $baseCurrency = CurrencyEnum::USD;
        $fetcher = new FreeCurrencyApiFetcher($baseCurrency);

        $this->expectExceptionMessage('API request failed: 403');

        Http::fake([
            'api.freecurrencyapi.com/v1/*' => Http::response(json_encode(['data' => null]), 403),
        ]);
        $fetcher->fetch(Carbon::yesterday());
    }
}