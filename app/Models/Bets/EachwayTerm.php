<?php

namespace App\Models\Bets;

use Database\Factories\Bets\EachwayTermFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $bet_id
 * @property float $place_fraction
 * @property int $places
 */
#[Fillable(['bet_id', 'place_fraction', 'places'])]
class EachwayTerm extends Model
{
    /** @use HasFactory<EachwayTermFactory> */
    use HasFactory, HasUuids;

    /** @return BelongsTo<Bet, $this> */
    public function bet(): BelongsTo
    {
        return $this->belongsTo(Bet::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'place_fraction' => 'decimal:4',
            'places' => 'integer',
        ];
    }
}
