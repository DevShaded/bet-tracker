<?php

namespace App\Models\Bets;

use Database\Factories\Bets\BetLegFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $bet_id
 * @property string $sport
 * @property string|null $competition
 * @property string|null $venue
 * @property string $selection
 * @property string $market
 * @property string|float $odds
 * @property string|null $event_starts_at
 * @property string|null $result
 * @property Carbon|null $settled_at
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['bet_id', 'sport', 'competition', 'venue', 'selection', 'market', 'odds', 'event_starts_at', 'result', 'settled_at', 'sort_order'])]
class BetLeg extends Model
{
    /** @use HasFactory<BetLegFactory> */
    use HasFactory, HasUuids;

    /** @return BelongsTo<Bet, $this> */
    public function bet(): BelongsTo
    {
        return $this->belongsTo(Bet::class);
    }

    /** @return array<string, string> */
    public function casts(): array
    {
        return [
            'odds' => 'decimal:4',
            'event_starts_at' => 'datetime',
            'settled_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }
}
