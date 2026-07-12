<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Weather;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function import()
    {
        set_time_limit(300);

        $countries = Country::doesntHave('weather')
            ->limit(5)
            ->get();

        if ($countries->isEmpty()) {
            return redirect()
                ->route('countries.index')
                ->with('success', 'Semua weather berhasil diimport.');
        }

        foreach ($countries as $country) {

            if (!$country->latitude || !$country->longitude) {
                continue;
            }

            try {

                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->get(
                        'https://api.open-meteo.com/v1/forecast',
                        [
                            'latitude' => $country->latitude,
                            'longitude' => $country->longitude,
                            'current' => 'temperature_2m,wind_speed_10m,weather_code',
                        ]
                    );

                if (!$response->successful()) {
                    continue;
                }

                $current = $response->json()['current'] ?? [];

                Weather::updateOrCreate(
                    [
                        'country_id' => $country->id,
                    ],
                    [
                        'temperature' => $current['temperature_2m'] ?? null,
                        'wind_speed' => $current['wind_speed_10m'] ?? null,
                        'weather_code' => $current['weather_code'] ?? null,
                    ]
                );

                // jeda 0,2 detik supaya tidak membanjiri API
                usleep(200000);

            } catch (\Exception $e) {
                continue;
            }
        }

        return redirect()
            ->route('countries.index')
            ->with('success', '5 data weather berhasil diimport. Klik Import Weather lagi.');
    }
}