<?php

namespace App\Models\Tipster;

use App\Models\Bets\Bet;
use App\Models\User;
use Database\Factories\Tipster\TipCategoryFactory;
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
 * @property string|null $default_stake_percentage
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'name', 'default_stake_percentage'])]
class TipCategory extends Model
{
    /** @use HasFactory<TipCategoryFactory> */
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

    protected function casts(): array
    {
        return [
            'default_stake_percentage' => 'decimal:2',
        ];
    }
}
