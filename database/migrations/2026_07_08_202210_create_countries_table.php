<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique(); // Mendukung format kode negara (REST Countries)
            $table->string('capital')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->unsignedBigInteger('population')->nullable();
            $table->string('currency')->nullable(); // Alternatif currency_code
            $table->string('flag')->nullable();
            $table->decimal('risk_score', 5, 2)->default(0); // Baseline untuk scoring engine
            
            // Tambahan kolom baru untuk data analitik tren (World Bank API)
            $table->json('gdp_trend')->nullable();       // Tren data GDP berkelanjutan
            $table->json('inflation_trend')->nullable(); // Tren data Inflasi makro
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};