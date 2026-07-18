<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SentimentSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh kata positif sesuai spesifikasi proyek[cite: 1]
        $positiveWords = [
            ['word' => 'growth'],
            ['word' => 'increase'],
            ['word' => 'profit'],
            ['word' => 'stable'],
            ['word' => 'improve'],
        ];

        // Contoh kata negatif sesuai spesifikasi proyek[cite: 1]
        $negativeWords = [
            ['word' => 'war'],
            ['word' => 'crisis'],
            ['word' => 'inflation'],
            ['word' => 'delay'],
            ['word' => 'disaster'],
        ];

        DB::table('positive_words')->insert($positiveWords);
        DB::table('negative_words')->insert($negativeWords);
    }
}