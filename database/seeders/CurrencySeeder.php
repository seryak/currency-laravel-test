<?php

namespace Database\Seeders;

use App\Enums\CurrencyEnum;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencyData = [
            'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$'],
            'BGN' => ['name' => 'Bulgarian Lev', 'symbol' => 'лв'],
            'BRL' => ['name' => 'Brazilian Real', 'symbol' => 'R$'],
            'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$'],
            'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'CHF'],
            'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥'],
            'CZK' => ['name' => 'Czech Koruna', 'symbol' => 'Kč'],
            'DKK' => ['name' => 'Danish Krone', 'symbol' => 'kr'],
            'EUR' => ['name' => 'Euro', 'symbol' => '€'],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£'],
            'HKD' => ['name' => 'Hong Kong Dollar', 'symbol' => 'HK$'],
            'HRK' => ['name' => 'Croatian Kuna', 'symbol' => 'kn'],
            'HUF' => ['name' => 'Hungarian Forint', 'symbol' => 'Ft'],
            'IDR' => ['name' => 'Indonesian Rupiah', 'symbol' => 'Rp'],
            'ILS' => ['name' => 'Israeli Shekel', 'symbol' => '₪'],
            'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹'],
            'ISK' => ['name' => 'Icelandic Króna', 'symbol' => 'kr'],
            'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥'],
            'KRW' => ['name' => 'South Korean Won', 'symbol' => '₩'],
            'MXN' => ['name' => 'Mexican Peso', 'symbol' => 'MX$'],
            'MYR' => ['name' => 'Malaysian Ringgit', 'symbol' => 'RM'],
            'NOK' => ['name' => 'Norwegian Krone', 'symbol' => 'kr'],
            'NZD' => ['name' => 'New Zealand Dollar', 'symbol' => 'NZ$'],
            'PHP' => ['name' => 'Philippine Peso', 'symbol' => '₱'],
            'PLN' => ['name' => 'Polish Złoty', 'symbol' => 'zł'],
            'RON' => ['name' => 'Romanian Leu', 'symbol' => 'lei'],
            'RUB' => ['name' => 'Russian Ruble', 'symbol' => '₽'],
            'SEK' => ['name' => 'Swedish Krona', 'symbol' => 'kr'],
            'SGD' => ['name' => 'Singapore Dollar', 'symbol' => 'S$'],
            'THB' => ['name' => 'Thai Baht', 'symbol' => '฿'],
            'TRY' => ['name' => 'Turkish Lira', 'symbol' => '₺'],
            'USD' => ['name' => 'US Dollar', 'symbol' => '$'],
            'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R'],
        ];

        foreach (CurrencyEnum::cases() as $currencyEnum) {
            $code = $currencyEnum->name;
            if (isset($currencyData[$code])) {
                Currency::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $currencyData[$code]['name'],
                        'symbol' => $currencyData[$code]['symbol'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}