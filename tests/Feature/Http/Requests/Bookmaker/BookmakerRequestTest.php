<?php

use App\Http\Requests\Bookmaker\BookmakerRequest;
use Illuminate\Support\Facades\Validator;

test('validates three character currency codes', function () {
    $rules = (new BookmakerRequest)->rules();

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
    $request = new class extends BookmakerRequest
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
