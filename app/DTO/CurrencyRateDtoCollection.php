<?php

namespace App\DTO;

use App\Enums\CurrencyEnum;
use Illuminate\Support\Collection;

class CurrencyRateDtoCollection
{
    private Collection $items;

    public function __construct(array $items = [])
    {
        $this->items = collect([]);

        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add(CurrencyRateDto $dto): void
    {
        $this->items->add($dto);
    }

    public function each(callable $callback): void
    {
        $this->items->each($callback);
    }

    public function get(CurrencyEnum $currencyEnum): CurrencyRateDto|null
    {
        return $this->items->where('currencyCode', $currencyEnum)->first();
    }

    public function all(): array
    {
        return $this->items->all();
    }
}