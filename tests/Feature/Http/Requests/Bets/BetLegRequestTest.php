<?php

use App\Http\Requests\Bets\BetLegRequest;
use App\Models\Bankroll\Bankroll;
use App\Models\Bets\Bet;
use App\Models\Bets\BetLeg;
use Illuminate\Database\QueryException;
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
        'sport' => 'Football',
        'competition' => null,
        'venue' => null,
        'selection' => 'Home team',
        'market' => 'Match winner',
        'odds' => '2.1234',
        'event_starts_at' => '2026-08-22 12:00:00',
        'result' => null,
        'settled_at' => '2026-08-22 14:00:00',
        'sort_order' => 1,
    ], (new BetLegRequest)->rules());

    expect($validator->passes())->toBeTrue();
});

test('rejects an invalid event start date', function () {
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
        'sport' => 'Football',
        'competition' => null,
        'venue' => null,
        'selection' => 'Home team',
        'market' => 'Match winner',
        'odds' => '2.00',
        'event_starts_at' => 'not-a-date',
        'result' => null,
        'settled_at' => '2026-08-22 14:00:00',
        'sort_order' => 1,
    ], (new BetLegRequest)->rules());

    expect($validator->errors()->has('event_starts_at'))->toBeTrue();
});

test('rejects an invalid settled date', function () {
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
        'sport' => 'Football',
        'competition' => null,
        'venue' => null,
        'selection' => 'Home team',
        'market' => 'Match winner',
        'odds' => '2.00',
        'event_starts_at' => '2026-08-22 12:00:00',
        'result' => null,
        'settled_at' => 'not-a-date',
        'sort_order' => 1,
    ], (new BetLegRequest)->rules());

    expect($validator->errors()->has('settled_at'))->toBeTrue();
});

test('rejects duplicate sort orders for the same bet', function () {
    $bet = Bet::factory()->create();

    BetLeg::factory()->for($bet)->create(['sort_order' => 1]);

    expect(fn () => BetLeg::factory()->for($bet)->create(['sort_order' => 1]))
        ->toThrow(QueryException::class);
});
