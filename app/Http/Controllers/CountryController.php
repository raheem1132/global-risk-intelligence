<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();

        return view('countries.index', compact('countries'));
    }

    public function import()
    {
        $response = Http::withoutVerifying()
            ->acceptJson()
            ->get('https://countries.dev/countries');

        if (!$response->successful()) {
            return redirect()->route('countries.index')
                ->with('error', 'Gagal mengambil data. Status: ' . $response->status());
        }

        $countries = $response->json();

        foreach ($countries as $item) {

            $code = strtoupper($item['alpha2Code'] ?? '');

            Country::updateOrCreate(
                [
                    'code' => $code,
                ],
                [
                    'name'       => $item['name'] ?? '',
                    'capital'    => $item['capital'] ?? null,
                    'region'     => $item['region'] ?? null,
                    'subregion'  => $item['subregion'] ?? null,
                    'population' => $item['population'] ?? 0,

                    'currency' => $item['currencies'][0]['code'] ?? null,

                    // URL bendera
                    'flag' => "https://flagcdn.com/w80/" . strtolower($code) . ".png",

                    // Koordinat
                    'latitude' => $item['latlng'][0] ?? null,
                    'longitude' => $item['latlng'][1] ?? null,

                    'risk_score' => 0,
                ]
            );
        }

        return redirect()->route('countries.index')
            ->with('success', 'Data negara berhasil diimport.');
    }
}