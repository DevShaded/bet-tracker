<?php

namespace Database\Factories\Tipster;

use App\Models\Tipster\Tipster;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Tipster>
 */
class TipsterFactory extends Factory
{
    protected $model = Tipster::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'source' => $this->faker->word(),
            'notes' => $this->faker->text(200),
            'is_active' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
