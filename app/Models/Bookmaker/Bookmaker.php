<?php

namespace App\Models\Bookmaker;

use App\Models\Bets\Bet;
use App\Models\User;
use Database\Factories\Bookmaker\BookmakerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string|float|null $minimum_stake
 * @property string|null $currency
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'name', 'minimum_stake', 'currency', 'is_active'])]
class Bookmaker extends Model
{
    /** @use HasFactory<BookmakerFactory> */
    use HasFactory, HasUuids;

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Bet, $this> */
    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'minimum_stake' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
