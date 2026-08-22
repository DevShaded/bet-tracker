<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bankroll_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bankroll_id')
                ->constrained('bankrolls')
                ->cascadeOnDelete();

            $table->foreignUuid('bet_id')
                ->nullable()
                ->constrained('bets')
                ->nullOnDelete();
            $table->enum('type', ['deposit', 'withdrawal', 'stake', 'payout', 'refund', 'adjustment_credit', 'adjustment_debit', 'bonus']);
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bankroll_transactions');
    }
};
