<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bet_components', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bet_id')
                ->references('id')
                ->on('bets')
                ->cascadeOnDelete();
            $table->string('type');
            $table->decimal('stake', 12, 2);
            $table->decimal('odds', 10, 4);
            $table->string('result')->nullable();
            $table->decimal('return_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bet_components');
    }
};
