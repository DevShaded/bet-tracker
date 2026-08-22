<?php

namespace App\Models\Bankroll;

use App\Models\Bets\Bet;
use App\Models\User;
use Database\Factories\Bankroll\BankrollFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $user_id
 * @property string $name
 * @property string $currency
 * @property string|float $starting_balance
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'name', 'currency', 'starting_balance', 'is_active'])]
class Bankroll extends Model
{
    /** @use HasFactory<BankrollFactory> */
    use HasFactory, HasUuids;

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Bet, $this> */
    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class, 'bankroll_id');
    }

    /** @return HasMany<BankrollTransaction, $this> */
    public function transactions(): HasMany
    {
        return $this->hasMany(BankrollTransaction::class, 'bankroll_id');
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'starting_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
