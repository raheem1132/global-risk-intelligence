<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel untuk cache berita agar tidak kena limit API[cite: 1]
        Schema::create('news_cache', function (Blueprint $table) {
            $table->id();
            $table->string('country_name');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url');
            $table->string('sentiment')->default('Neutral'); // Menyimpan hasil Lexicon Based Sentiment[cite: 1]
            $table->integer('positive_score')->default(0);
            $table->integer('negative_score')->default(0);
            $table->timestamp('fetched_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_cache');
    }
};