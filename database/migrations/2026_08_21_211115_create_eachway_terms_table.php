<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eachway_terms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bet_id')
                ->unique()
                ->references('id')
                ->on('bets')
                ->cascadeOnDelete();
            $table->decimal('place_fraction', 5, 4);
            $table->unsignedSmallInteger('places');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eachway_terms');
    }
};
