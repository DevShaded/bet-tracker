<?php

namespace Database\Factories\Bankroll;

use App\Bankroll\TransactionType;
use App\Models\Bankroll\Bankroll;
use App\Models\Bankroll\BankrollTransaction;
use App\Models\Bets\Bet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<BankrollTransaction> */
class BankrollTransactionFactory extends Factory
{
    protected $model = BankrollTransaction::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(array_column(TransactionType::cases(), 'value')),
            'amount' => $this->faker->randomFloat(min: 1, max: 1000),
            'description' => $this->faker->text(),
            'occurred_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'bankroll_id' => Bankroll::factory(),
            'bet_id' => fn (array $attributes) => Bet::factory()->state([
                'bankroll_id' => $attributes['bankroll_id'],
            ]),
        ];
    }
}
