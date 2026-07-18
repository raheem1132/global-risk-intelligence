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
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->string('port_name', 150);   // Nama Pelabuhan
            $table->string('country_code', 10); // Kode Negara Pelabuhan[cite: 1]
            $table->decimal('latitude', 10, 8);  // Koordinat Lintang Peta[cite: 1]
            $table->decimal('longitude', 11, 8); // Koordinat Bujur Peta[cite: 1]
            $table->string('risk_level', 20)->default('Low'); // Status Risiko Logistik[cite: 1]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};