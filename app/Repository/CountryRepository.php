<?php

namespace App\Repository;

use App\Country;
use Illuminate\Support\Collection;

class CountryRepository
{
    /**
     * @return Collection<Country>
     */
    public function getAllCountries(): Collection
    {
        return Country::query()
            ->orderBy('name')
            ->get();
    }

    public function getCountryByName(string $name): ?Country
    {
        return Country::query()
            ->where('name', $name)
            ->first();
    }
}
