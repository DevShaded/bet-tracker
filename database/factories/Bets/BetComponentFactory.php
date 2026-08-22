<?php

namespace Database\Factories\Bets;

use App\Models\Bets\Bet;
use App\Models\Bets\BetComponent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<BetComponent> */
class BetComponentFactory extends Factory
{
    protected $model = BetComponent::class;

    public function definition()
    {
        return [
            'type' => $this->faker->word(),
            'stake' => $this->faker->randomFloat(2, 0, 9_999_999_999.99),
            'odds' => $this->faker->randomFloat(4, 0, 999_999.9999),
            'result' => $this->faker->word(),
            'return_amount' => $this->faker->randomFloat(2, 0, 999_999.99),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'bet_id' => Bet::factory(),
        ];
    }
}
