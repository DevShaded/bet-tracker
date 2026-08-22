<?php

use App\Http\Requests\Bets\EachwayTermRequest;
use App\Models\Bankroll\Bankroll;
use App\Models\Bets\Bet;
use Illuminate\Support\Facades\Validator;

test('accepts a four-decimal place fraction', function () {
    $bankroll = Bankroll::factory()->create([
        'starting_balance' => '100.00',
    ]);
    $bet = Bet::factory()->create([
        'user_id' => $bankroll->user_id,
        'bankroll_id' => $bankroll->id,
        'stake' => '10.00',
        'combined_odds' => '2.0000',
        'actual_return' => '20.00',
    ]);

    $validator = Validator::make([
        'bet_id' => $bet->id,
        'place_fraction' => '0.2500',
        'places' => 3,
    ], (new EachwayTermRequest)->rules());

    expect($validator->passes())->toBeTrue();
});
