<?php

namespace Database\Factories\Bets;

use App\Bet\BetStatus;
use App\Bet\BetType;
use App\Models\Bankroll\Bankroll;
use App\Models\Bets\Bet;
use App\Models\Bookmaker\Bookmaker;
use App\Models\Tipster\TipCategory;
use App\Models\Tipster\Tipster;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<Bet> */
class BetFactory extends Factory
{
    protected $model = Bet::class;

    public function definition()
    {
        return [
            'bet_type' => $this->faker->randomElement(array_column(BetType::cases(), 'value')),
            'status' => $this->faker->randomElement(array_column(BetStatus::cases(), 'value')),
            'stake' => $this->faker->randomFloat(min: 0, max: 100),
            'unit_stake' => null,
            'combined_odds' => $this->faker->randomFloat(min: 0, max: 20),
            'actual_return' => $this->faker->randomFloat(),
            'placed_at' => Carbon::now(),
            'settled_at' => Carbon::now()->addHours(rand(1, 24)),
            'notes' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'bankroll_id' => fn (array $attributes) => Bankroll::factory()->state([
                'user_id' => $attributes['user_id'],
            ]),
            'bookmaker_id' => fn (array $attributes) => Bookmaker::factory()->state([
                'user_id' => $attributes['user_id'],
            ]),
            'tipster_id' => fn (array $attributes) => Tipster::factory()->state([
                'user_id' => $attributes['user_id'],
            ]),
            'tip_category_id' => fn (array $attributes) => TipCategory::factory()->state([
                'user_id' => $attributes['user_id'],
            ]),
        ];
    }
}
