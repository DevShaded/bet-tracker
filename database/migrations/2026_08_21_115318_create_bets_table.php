<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreignUuid('bankroll_id')
                ->references('id')
                ->on('bankrolls')
                ->cascadeOnDelete();
            $table->foreignUuid('bookmaker_id')
                ->references('id')
                ->on('bookmakers')
                ->cascadeOnDelete();
            $table->foreignUuid('tipster_id')
                ->nullable()
                ->references('id')
                ->on('tipsters')
                ->nullOnDelete();
            $table->foreignUuid('tip_category_id')
                ->nullable()
                ->references('id')
                ->on('tip_categories')
                ->nullOnDelete();

            $table->enum('bet_type', ['single', 'each_way', 'double', 'accumulator', 'system'])->default('single');
            $table->enum('status', ['draft', 'pending', 'won', 'lost', 'partially_won', 'void', 'cancelled', 'cashout'])->default('draft');
            $table->decimal('stake', 12, 2);
            $table->decimal('unit_stake', 12, 2)->nullable();
            $table->decimal('combined_odds', 10, 4)->nullable();
            $table->decimal('actual_return', 12, 2)->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
