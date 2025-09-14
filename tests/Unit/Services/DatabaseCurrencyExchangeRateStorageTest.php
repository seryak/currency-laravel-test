<?php

namespace Tests\Unit\Services;

use App\DTO\CurrencyRateDto;
use App\DTO\CurrencyRateDtoCollection;
use App\Enums\CurrencyEnum;
use App\Models\ExchangeRate;
use App\Services\DatabaseCurrencyExchangeRateStorage;
use Carbon\Carbon;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use ReflectionClass;

class DatabaseCurrencyExchangeRateStorageTest extends TestCase
{
    use RefreshDatabase;
    private $storage;
    private $currencyMock;
    private $exchangeRateMock;
    private $dbMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CurrencySeeder::class);

        $this->storage = new DatabaseCurrencyExchangeRateStorage();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testConstructorLoadsCurrencyDictionary()
    {
        $reflection = new ReflectionClass(DatabaseCurrencyExchangeRateStorage::class);
        $property = $reflection->getProperty('currencyDictionary');
        $property->setAccessible(true);

        $dictionary = $property->getValue($this->storage);
        foreach ($dictionary as $currency => $id) {
            $this->assertIsInt($id);
            $this->assertTrue(CurrencyEnum::tryFrom($currency) !== null);
        }
    }

    public function testSaveWithValidRates()
    {
        $this->seed(CurrencySeeder::class);
        $dto1 = new CurrencyRateDto(CurrencyEnum::USD, CurrencyEnum::USD, 1.0, Carbon::today());
        $dto2 = new CurrencyRateDto(CurrencyEnum::USD, CurrencyEnum::EUR, 0.85, Carbon::today());

        $collection = new CurrencyRateDtoCollection([$dto1, $dto2]);

        $this->storage->save($collection);

        $collection = ExchangeRate::all();
        $this->assertCount(2, $collection);
    }
}