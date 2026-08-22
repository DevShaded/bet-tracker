<?php

use App\Http\Requests\Bankroll\BankrollRequest;
use App\Models\Bankroll\Bankroll;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

test('accepts valid bankroll data', function () {
    $user = User::factory()->create();
    $request = new BankrollRequest;
    $request->setUserResolver(fn () => $user);

    $validator = Validator::make([
        'name' => 'Main bankroll',
        'currency' => 'USD',
        'starting_balance' => '100.00',
        'is_active' => true,
    ], $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('validates three character currency codes', function () {
    $user = User::factory()->create();
    $request = new BankrollRequest;
    $request->setUserResolver(fn () => $user);
    $rules = $request->rules();

    expect(Validator::make(
        ['currency' => 'USD'],
        ['currency' => $rules['currency']],
    )->passes())->toBeTrue()
        ->and(Validator::make(
            ['currency' => 'US'],
            ['currency' => $rules['currency']],
        )->fails())->toBeTrue();
});

test('normalizes currency codes to uppercase', function () {
    $user = User::factory()->create();
    $request = new class extends BankrollRequest
    {
        public function prepareCurrencyForValidation(): void
        {
            $this->prepareForValidation();
        }
    };
    $request->setUserResolver(fn () => $user);
    $request->merge(['currency' => 'nok']);

    $request->prepareCurrencyForValidation();

    expect($request->currency)->toBe('NOK');
});

test('rejects malformed currency values without throwing', function () {
    $user = User::factory()->create();
    $request = new class extends BankrollRequest
    {
        public function prepareCurrencyForValidation(): void
        {
            $this->prepareForValidation();
        }
    };
    $request->setUserResolver(fn () => $user);
    $request->merge(['currency' => ['USD']]);

    $request->prepareCurrencyForValidation();

    $validator = Validator::make(
        ['currency' => $request->input('currency')],
        ['currency' => $request->rules()['currency']],
    );

    expect($validator->fails())->toBeTrue();
});

test('scopes bankroll name uniqueness to the user', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    Bankroll::factory()->create([
        'user_id' => $owner->id,
        'name' => 'Main bankroll',
    ]);

    $ownerRequest = new BankrollRequest;
    $ownerRequest->setUserResolver(fn () => $owner);
    $otherUserRequest = new BankrollRequest;
    $otherUserRequest->setUserResolver(fn () => $otherUser);

    $ownerValidator = Validator::make(
        ['name' => 'Main bankroll'],
        ['name' => $ownerRequest->rules()['name']],
    );
    $otherUserValidator = Validator::make(
        ['name' => 'Main bankroll'],
        ['name' => $otherUserRequest->rules()['name']],
    );

    expect($ownerValidator->fails())->toBeTrue()
        ->and($otherUserValidator->passes())->toBeTrue();
});

test('rejects invalid bankroll names', function (mixed $name) {
    $user = User::factory()->create();
    $request = new BankrollRequest;
    $request->setUserResolver(fn () => $user);

    $validator = Validator::make(
        ['name' => $name],
        ['name' => $request->rules()['name']],
    );

    expect($validator->fails())->toBeTrue();
})->with([
    'numeric' => 1234,
    'too short' => 'ab',
    'too long' => str_repeat('a', 51),
]);

test('rejects invalid starting balances', function (mixed $startingBalance) {
    $user = User::factory()->create();
    $request = new BankrollRequest;
    $request->setUserResolver(fn () => $user);

    $validator = Validator::make(
        ['starting_balance' => $startingBalance],
        ['starting_balance' => $request->rules()['starting_balance']],
    );

    expect($validator->fails())->toBeTrue();
})->with([
    'database overflow' => '10000000000.00',
    'incorrect precision' => '100.0',
    'not numeric' => 'invalid',
]);
