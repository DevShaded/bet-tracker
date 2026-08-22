<?php

use App\Http\Requests\Bankroll\BankrollRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

test('validates a user by their id', function () {
    $user = User::factory()->create();

    $validator = Validator::make([
        'user_id' => $user->id,
        'name' => 'Main bankroll',
        'currency' => 'USD',
        'starting_balance' => '100.00',
        'is_active' => true,
    ], (new BankrollRequest)->rules());

    expect($validator->passes())->toBeTrue();
});

test('validates three character currency codes', function () {
    $rules = (new BankrollRequest)->rules();

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
    $request = new class extends BankrollRequest
    {
        public function prepareCurrencyForValidation(): void
        {
            $this->prepareForValidation();
        }
    };
    $request->merge(['currency' => 'nok']);

    $request->prepareCurrencyForValidation();

    expect($request->currency)->toBe('NOK');
});
