<?php

namespace Database\Factories\Bankroll;

use App\Models\Bankroll\Bankroll;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Bankroll>
 */
class BankrollFactory extends Factory
{
    protected $model = Bankroll::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'currency' => $this->faker->currencyCode(),
            'starting_balance' => $this->faker->randomFloat(),
            'is_active' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
