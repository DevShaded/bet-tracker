<?php

use App\Http\Requests\Bankroll\BankrollTransactionRequest;
use App\Models\Bankroll\Bankroll;
use App\Models\Bets\Bet;
use Illuminate\Support\Facades\Validator;

test('validates a bankroll by its id', function () {
    $bankroll = Bankroll::factory()->create([
        'starting_balance' => '100.00',
    ]);

    $validator = Validator::make([
        'bankroll_id' => $bankroll->id,
        'bet_id' => null,
        'type' => 'deposit',
        'amount' => '100.00',
        'description' => null,
        'occurred_at' => '2026-08-21',
    ], (new BankrollTransactionRequest)->rules());

    expect($validator->passes())->toBeTrue();
});

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
        'bankroll_id' => $bankroll->id,
        'bet_id' => $bet->id,
        'type' => 'deposit',
        'amount' => '100.00',
        'description' => null,
        'occurred_at' => '2026-08-21',
    ], (new BankrollTransactionRequest)->rules());

    expect($validator->passes())->toBeTrue();
});

test('requires transaction amounts to be positive', function (string $amount) {
    $validator = Validator::make(
        ['amount' => $amount],
        ['amount' => (new BankrollTransactionRequest)->rules()['amount']],
    );

    expect($validator->fails())->toBeTrue();
})->with([
    'zero' => '0.00',
    'negative' => '-1.00',
]);
