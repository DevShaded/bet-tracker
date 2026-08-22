<?php

namespace Database\Factories\Tipster;

use App\Models\Tipster\TipCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<TipCategory>
 */
class TipCategoryFactory extends Factory
{
    protected $model = TipCategory::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'default_stake_percentage' => $this->faker->randomFloat(min: 0, max: 100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
