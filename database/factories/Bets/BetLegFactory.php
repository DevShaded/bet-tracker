<?php

namespace Database\Factories\Bets;

use App\Models\Bets\Bet;
use App\Models\Bets\BetLeg;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<BetLeg> */
class BetLegFactory extends Factory
{
    protected $model = BetLeg::class;

    public function definition()
    {
        $placedAt = Carbon::now();

        return [
            'sport' => $this->faker->word(),
            'competition' => $this->faker->word(),
            'venue' => $this->faker->word(),
            'selection' => $this->faker->word(),
            'market' => $this->faker->word(),
            'odds' => $this->faker->randomFloat(4, 0, 999_999.9999),
            'event_starts_at' => $this->faker->optional()->dateTimeBetween(
                $placedAt->copy()->addSecond(),
                $placedAt->copy()->addYear(),
            ),
            'result' => $this->faker->word(),
            'settled_at' => function (array $attributes) use ($placedAt) {
                $settlesAfter = Carbon::parse($attributes['event_starts_at'] ?? $placedAt)->max($placedAt);

                return $this->faker->optional()->dateTimeBetween(
                    $settlesAfter->copy()->addSecond(),
                    $settlesAfter->copy()->addYear(),
                );
            },
            'sort_order' => $this->faker->numberBetween(0, 65_535),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'bet_id' => Bet::factory()->state(['placed_at' => $placedAt]),
        ];
    }
}
