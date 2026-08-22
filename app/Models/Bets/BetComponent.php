<?php

namespace App\Models\Bets;

use Database\Factories\Bets\BetComponentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $bet_id
 * @property string $type
 * @property string|float $stake
 * @property string|float $odds
 * @property string|null $result
 * @property string|float|null $return_amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['bet_id', 'type', 'stake', 'odds', 'result', 'return_amount'])]
class BetComponent extends Model
{
    /** @use HasFactory<BetComponentFactory> */
    use HasFactory, HasUuids;

    /** @return BelongsTo<Bet, $this> */
    public function bet(): BelongsTo
    {
        return $this->belongsTo(Bet::class);
    }

    protected function casts(): array
    {
        return [
            'stake' => 'decimal:2',
            'odds' => 'decimal:4',
            'return_amount' => 'decimal:2',
        ];
    }
}
