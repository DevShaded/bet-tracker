<?php

use App\Http\Requests\Bets\BetComponentRequest;
use App\Models\Bankroll\Bankroll;
use App\Models\Bets\Bet;
use Illuminate\Support\Facades\Validator;

test('validates a bet by its id', function () {
    $bankroll = Bankroll::factory()->create([
        'starting_balance' => '100.00',
    ]);
    $bet = Bet::factory()->create([
        'user_id' => $bankroll->user_id,
        'bankroll_id' => $bankroll->id,
        'stake' => '10.00',
        'combined_odds' => '2.00',
        'actual_return' => '20.00',
    ]);

    $validator = Validator::make([
        'bet_id' => $bet->id,
        'type' => 'single',
        'stake' => '10.00',
        'odds' => '2.1234',
        'result' => null,
        'return_amount' => null,
    ], (new BetComponentRequest)->rules());

    expect($validator->passes())->toBeTrue();
});
