<?php

use App\Http\Requests\Bets\BetRequest;
use App\Models\Bankroll\Bankroll;
use App\Models\Bookmaker\Bookmaker;
use App\Models\Tipster\TipCategory;
use App\Models\Tipster\Tipster;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

test('validates a user by its id', function () {
    $user = User::factory()->create();

    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['user_id' => $user->id],
        ['user_id' => $rules['user_id']],
    );

    expect($validator->passes())->toBeTrue();
});

test('validates a bankroll by its id', function () {
    $bankroll = Bankroll::factory()->create([
        'starting_balance' => '100.00',
    ]);

    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['bankroll_id' => $bankroll->id],
        ['bankroll_id' => $rules['bankroll_id']],
    );

    expect($validator->passes())->toBeTrue();
});

test('validates a bookmaker by its id', function () {
    $bookmaker = Bookmaker::factory()->create([
        'minimum_stake' => '1.00',
        'currency' => 'USD',
    ]);

    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['bookmaker_id' => $bookmaker->id],
        ['bookmaker_id' => $rules['bookmaker_id']],
    );

    expect($validator->passes())->toBeTrue();
});

test('validates a tipster by its id', function () {
    $tipster = Tipster::factory()->create();

    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['tipster_id' => $tipster->id],
        ['tipster_id' => $rules['tipster_id']],
    );

    expect($validator->passes())->toBeTrue();
});

test('allows a bet without a tipster', function () {
    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['tipster_id' => null],
        ['tipster_id' => $rules['tipster_id']],
    );

    expect($validator->passes())->toBeTrue();
});

test('validates a tip category by its id', function () {
    $tipCategory = TipCategory::factory()->create([
        'default_stake_percentage' => '10.00',
    ]);

    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['tip_category_id' => $tipCategory->id],
        ['tip_category_id' => $rules['tip_category_id']],
    );

    expect($validator->passes())->toBeTrue();
});

test('allows a bet without a tip category', function () {
    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['tip_category_id' => null],
        ['tip_category_id' => $rules['tip_category_id']],
    );

    expect($validator->passes())->toBeTrue();
});

test('validates nullable unit stake with two decimal places', function (?string $unitStake, bool $passes) {
    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['unit_stake' => $unitStake],
        ['unit_stake' => $rules['unit_stake']],
    );

    expect($validator->passes())->toBe($passes);
})->with([
    'null' => [null, true],
    'two decimal places' => ['10.00', true],
    'one decimal place' => ['10.0', false],
]);

test('validates combined odds with four decimal places', function (string $combinedOdds, bool $passes) {
    $rules = (new BetRequest)->rules();
    $validator = Validator::make(
        ['combined_odds' => $combinedOdds],
        ['combined_odds' => $rules['combined_odds']],
    );

    expect($validator->passes())->toBe($passes);
})->with([
    'four decimal places' => ['2.3456', true],
    'two decimal places' => ['2.34', false],
]);
