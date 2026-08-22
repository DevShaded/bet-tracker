<?php

namespace Database\Factories\Bets;

use App\Models\Bets\Bet;
use App\Models\Bets\EachwayTerm;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<EachwayTerm> */
class EachwayTermFactory extends Factory
{
    protected $model = EachwayTerm::class;

    public function definition()
    {
        return [
            'place_fraction' => $this->faker->randomFloat(nbMaxDecimals: 4, min: -9.9999, max: 9.9999),
            'places' => $this->faker->numberBetween(0, 65535),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'bet_id' => Bet::factory(),
        ];
    }
}
