<?php

use App\Models\Bets\Bet;
use App\Models\Tipster\Tipster;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('tipster defines its bet relation', function () {
    $tipster = new Tipster;

    expect($tipster->bets())
        ->toBeInstanceOf(HasMany::class)
        ->and($tipster->bets()->getRelated())->toBeInstanceOf(Bet::class);
});
