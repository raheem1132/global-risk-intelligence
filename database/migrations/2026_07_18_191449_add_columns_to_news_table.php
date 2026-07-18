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
        Schema::table('news', function (Blueprint $table) {

            $table->foreignId('country_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title')->after('country_id');

            $table->text('description')->nullable()->after('title');

            $table->string('url')->nullable()->after('description');

            $table->string('image')->nullable()->after('url');

            $table->string('source')->nullable()->after('image');

            $table->dateTime('published_at')->nullable()->after('source');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {

            $table->dropForeign(['country_id']);

            $table->dropColumn([
                'country_id',
                'title',
                'description',
                'url',
                'image',
                'source',
                'published_at',
            ]);

        });
    }
};