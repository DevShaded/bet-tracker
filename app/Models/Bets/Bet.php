<?php

namespace App\Models\Bets;

use App\Enums\Bet\BetStatus;
use App\Enums\Bet\BetType;
use App\Models\Bankroll\Bankroll;
use App\Models\Bankroll\BankrollTransaction;
use App\Models\Bookmaker\Bookmaker;
use App\Models\Tipster\TipCategory;
use App\Models\Tipster\Tipster;
use App\Models\User;
use Database\Factories\Bets\BetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'bankroll_id', 'bookmaker_id', 'tipster_id', 'tip_category_id', 'bet_type', 'status', 'stake', 'unit_stake', 'combined_odds', 'actual_return', 'placed_at', 'settled_at', 'notes'])]
class Bet extends Model
{
    /** @use HasFactory<BetFactory> */
    use HasFactory, HasUuids;

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Bankroll, $this> */
    public function bankroll(): BelongsTo
    {
        return $this->belongsTo(Bankroll::class);
    }

    /** @return BelongsTo<Bookmaker, $this> */
    public function bookmaker(): BelongsTo
    {
        return $this->belongsTo(Bookmaker::class);
    }

    /** @return BelongsTo<Tipster, $this> */
    public function tipster(): BelongsTo
    {
        return $this->belongsTo(Tipster::class);
    }

    /** @return BelongsTo<TipCategory, $this> */
    public function tipCategory(): BelongsTo
    {
        return $this->belongsTo(TipCategory::class);
    }

    /** @return HasMany<BetLeg, $this> */
    public function legs(): HasMany
    {
        return $this->hasMany(BetLeg::class, 'bet_id')->orderBy('sort_order');
    }

    /** @return HasMany<BetComponent, $this> */
    public function components(): HasMany
    {
        return $this->hasMany(BetComponent::class, 'bet_id');
    }

    /** @return HasOne<EachwayTerm, $this> */
    public function eachwayTerm(): HasOne
    {
        return $this->hasOne(EachwayTerm::class, 'bet_id');
    }

    /** @return HasMany<BankrollTransaction, $this> */
    public function transactions(): HasMany
    {
        return $this->hasMany(BankrollTransaction::class, 'bet_id');
    }

    protected function casts(): array
    {
        return [
            'stake' => 'decimal:2',
            'unit_stake' => 'decimal:2',
            'combined_odds' => 'decimal:4',
            'actual_return' => 'decimal:2',
            'placed_at' => 'datetime',
            'settled_at' => 'datetime',
            'bet_type' => BetType::class,
            'status' => BetStatus::class,
        ];
    }
}
