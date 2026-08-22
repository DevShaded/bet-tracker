<?php

namespace Database\Factories\Bookmaker;

use App\Models\Bookmaker\Bookmaker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Bookmaker>
 */
class BookmakerFactory extends Factory
{
    protected $model = Bookmaker::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'minimum_stake' => $this->faker->randomFloat(),
            'currency' => $this->faker->currencyCode(),
            'is_active' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
