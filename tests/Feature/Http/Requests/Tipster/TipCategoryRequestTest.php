<?php

use App\Http\Requests\Tipster\TipCategoryRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

test('accepts a null default stake percentage', function () {
    $request = new TipCategoryRequest;
    $request->merge([
        'user_id' => User::factory()->create()->id,
        'name' => 'Football',
        'default_stake_percentage' => null,
    ]);

    $validator = Validator::make($request->all(), $request->rules());

    expect($validator->passes())->toBeTrue();
});
