<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function getNews(Request $request)
    {
        // 1. Ambil input negara (default: indonesia)
        $country = $request->query('country', 'indonesia');
        $countryClean = strtolower(trim($country));

        // 2. Ambil data berita dari database cache (seperti log query SQL-mu)
        $newsCache = DB::table('news_cache')
            ->where('country_name', $countryClean)
            ->get()
            ->toArray();

        // Ambil data kamus kata untuk hitung sentimen di tingkat backend (Lexicon Based)
        $positiveWords = DB::table('positive_words')->pluck('word')->toArray();
        $negativeWords = DB::table('negative_words')->pluck('word')->toArray();

        $totalNews = count($newsCache);
        $totalNegativeNews = 0;

        // Proses ulang sentimen jika diperlukan via backend
        foreach ($newsCache as $article) {
            $text = strtolower(($article->title ?? '') . ' ' . ($article->description ?? ''));
            $words = str_word_count($text, 1);
            
            $posScore = 0;
            $negScore = 0;

            foreach ($words as $word) {
                if (in_array($word, $positiveWords)) $posScore++;
                if (in_array($word, $negativeWords)) $negScore++;
            }

            if ($negScore > $posScore) {
                $totalNegativeNews++;
            }
        }

        // 3. JALANKAN ALGORITMA WEIGHTED RISK MODEL (Spesifikasi Proyek UAS)
        // Indikator Cuaca Ekstrem (Simulasi API Open-Meteo)
        $weatherRisk = rand(10, 80); 
        
        // Indikator Inflasi (Simulasi API World Bank)
        $inflationRisk = $countryClean === 'germany' ? 70 : ($countryClean === 'usa' ? 55 : 35); 
        
        // Indikator Sentimen Berita Geopolitik / Logistik
        $newsRisk = $totalNews > 0 ? round(($totalNegativeNews / $totalNews) * 100) : 25;
        
        // Indikator Fluktuasi Kurs (Simulasi API ExchangeRate)
        $currencyRisk = rand(15, 60);

        // Rumus Bobot UAS: Weather 30% + Inflation 20% + News Risk 40% + Currency 10%
        $finalRiskScore = round(
            ($weatherRisk * 0.30) + 
            ($inflationRisk * 0.20) + 
            ($newsRisk * 0.40) + 
            ($currencyRisk * 0.10)
        );

        // Tentukan Label Risiko
        $riskStatus = "Low Risk";
        if ($finalRiskScore >= 40 && $finalRiskScore < 70) $riskStatus = "Medium Risk";
        if ($finalRiskScore >= 70) $riskStatus = "High Risk";

        // 4. Kirim paket data lengkap ke Frontend Dashboard
        return view('news', [
            'data' => $newsCache,
            'country' => ucfirst($countryClean),
            'source' => $totalNews > 0 ? 'Cache Database' : 'API Live Engine',
            'riskScore' => $finalRiskScore,
            'riskStatus' => $riskStatus,
            'breakdown' => [
                'weather' => $weatherRisk,
                'inflation' => $inflationRisk,
                'news' => $newsRisk,
                'currency' => $currencyRisk
            ]
        ]);
    }
}