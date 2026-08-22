<?php

namespace App\Models\Bankroll;

use App\Enums\Bankroll\TransactionType;
use App\Models\Bets\Bet;
use Database\Factories\Bankroll\BankrollTransactionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $bankroll_id
 * @property string|null $bet_id
 * @property TransactionType $type
 * @property string|float $amount
 * @property string|null $description
 * @property Carbon $occurred_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['bankroll_id', 'bet_id', 'type', 'amount', 'description', 'occurred_at'])]
class BankrollTransaction extends Model
{
    /** @use HasFactory<BankrollTransactionFactory> */
    use HasFactory, HasUuids;

    /** @return BelongsTo<Bankroll, $this> */
    public function bankroll(): BelongsTo
    {
        return $this->belongsTo(Bankroll::class);
    }

    /** @return BelongsTo<Bet, $this> */
    public function bet(): BelongsTo
    {
        return $this->belongsTo(Bet::class);
    }

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'amount' => 'decimal:2',
            'occurred_at' => 'datetime',
        ];
    }
}
