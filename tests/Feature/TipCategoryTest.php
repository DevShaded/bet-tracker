<?php

use App\Models\Bets\Bet;
use App\Models\Tipster\TipCategory;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('tip category defines its bet relation', function () {
    $tipCategory = new TipCategory;

    expect($tipCategory->bets())
        ->toBeInstanceOf(HasMany::class)
        ->and($tipCategory->bets()->getRelated())->toBeInstanceOf(Bet::class);
});

test('tip category casts its default stake percentage to two decimals', function () {
    $tipCategory = new TipCategory([
        'default_stake_percentage' => '10',
    ]);

    expect($tipCategory->default_stake_percentage)
        ->toBe('10.00')
        ->and($tipCategory->getCasts()['default_stake_percentage'])->toBe('decimal:2');
});
