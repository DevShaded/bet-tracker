<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bet_legs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bet_id')
                ->references('id')
                ->on('bets')
                ->cascadeOnDelete();
            $table->string('sport');
            $table->string('competition')->nullable();
            $table->string('venue')->nullable();
            $table->string('selection');
            $table->string('market');
            $table->decimal('odds', 10, 4);
            $table->timestamp('event_starts_at')->nullable();
            $table->string('result')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->unsignedSmallInteger('sort_order');
            $table->unique(['bet_id', 'sort_order']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bet_legs');
    }
};
