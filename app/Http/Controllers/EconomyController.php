<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Economy;
use Illuminate\Support\Facades\Http;

class EconomyController extends Controller
{
    public function import()
    {
        set_time_limit(300);

        $countries = Country::doesntHave('economy')
            ->limit(5)
            ->get();

        if ($countries->isEmpty()) {
            return redirect()
                ->route('countries.index')
                ->with('success', 'Semua data ekonomi berhasil diimport.');
        }

        foreach ($countries as $country) {

            try {

                $code = strtolower($country->code);

                $gdp = Http::withoutVerifying()
                    ->timeout(15)
                    ->get("https://api.worldbank.org/v2/country/{$code}/indicator/NY.GDP.MKTP.CD", [
                        'format' => 'json',
                        'per_page' => 10,
                    ])
                    ->json();

                $inflation = Http::withoutVerifying()
                    ->timeout(15)
                    ->get("https://api.worldbank.org/v2/country/{$code}/indicator/FP.CPI.TOTL.ZG", [
                        'format' => 'json',
                        'per_page' => 10,
                    ])
                    ->json();

                $gdpValue = null;
                $year = null;

                if (isset($gdp[1])) {
                    foreach ($gdp[1] as $item) {
                        if (!is_null($item['value'])) {
                            $gdpValue = $item['value'];
                            $year = $item['date'];
                            break;
                        }
                    }
                }

                $inflationValue = null;

                if (isset($inflation[1])) {
                    foreach ($inflation[1] as $item) {
                        if (!is_null($item['value'])) {
                            $inflationValue = $item['value'];
                            break;
                        }
                    }
                }

                Economy::updateOrCreate(
                    [
                        'country_id' => $country->id,
                    ],
                    [
                        'gdp' => $gdpValue,
                        'inflation' => $inflationValue,
                        'population' => $country->population,
                        'exports' => null,
                        'imports' => null,
                        'year' => $year,
                    ]
                );

                usleep(300000);

            } catch (\Exception $e) {
                continue;
            }
        }

        return redirect()
            ->route('countries.index')
            ->with('success', '5 data ekonomi berhasil diimport. Klik Import Economy lagi.');
    }
}