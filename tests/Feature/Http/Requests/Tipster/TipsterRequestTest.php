<?php

use App\Http\Requests\Tipster\TipsterRequest;
use App\Models\Tipster\Tipster;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

test('rejects a duplicate name for the same user', function () {
    $tipster = Tipster::factory()->create([
        'name' => 'Winning Picks',
    ]);
    $request = new TipsterRequest;
    $request->merge([
        'user_id' => $tipster->user_id,
        'name' => $tipster->name,
    ]);

    $validator = Validator::make($request->all(), $request->rules());

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

test('accepts the same name for different users', function () {
    $tipster = Tipster::factory()->create([
        'name' => 'Winning Picks',
    ]);
    $user = User::factory()->create();
    $request = new TipsterRequest;
    $request->merge([
        'user_id' => $user->id,
        'name' => $tipster->name,
    ]);

    $validator = Validator::make($request->all(), $request->rules());

    expect($validator->passes())->toBeTrue();
});
