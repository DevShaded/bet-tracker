<?php

use App\Models\Bets\Bet;
use App\Models\Bookmaker\Bookmaker;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('bookmaker defines its relations', function () {
    $bookmaker = new Bookmaker;

    expect($bookmaker->user())
        ->toBeInstanceOf(BelongsTo::class)
        ->and($bookmaker->user()->getRelated())->toBeInstanceOf(User::class)
        ->and($bookmaker->bets())->toBeInstanceOf(HasMany::class)
        ->and($bookmaker->bets()->getRelated())->toBeInstanceOf(Bet::class);
});

test('user defines its bookmaker relation', function () {
    $user = new User;

    expect($user->bookmakers())
        ->toBeInstanceOf(HasMany::class)
        ->and($user->bookmakers()->getRelated())->toBeInstanceOf(Bookmaker::class);
});
