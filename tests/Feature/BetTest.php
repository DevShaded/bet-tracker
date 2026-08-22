<?php

use App\Models\Bankroll\BankrollTransaction;
use App\Models\Bets\Bet;
use App\Models\Bookmaker\Bookmaker;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('bet defines its bookmaker and transaction relations', function () {
    $bet = new Bet;

    expect($bet->bookmaker())
        ->toBeInstanceOf(BelongsTo::class)
        ->and($bet->bookmaker()->getRelated())->toBeInstanceOf(Bookmaker::class)
        ->and($bet->transactions())->toBeInstanceOf(HasMany::class)
        ->and($bet->transactions()->getRelated())->toBeInstanceOf(BankrollTransaction::class);
});

test('bet casts monetary values and odds to fixed precision', function () {
    $bet = new Bet;
    $bet->stake = 10;
    $bet->unit_stake = 5;
    $bet->combined_odds = 2.5;
    $bet->actual_return = 25;

    expect($bet->stake)->toBe('10.00')
        ->and($bet->unit_stake)->toBe('5.00')
        ->and($bet->combined_odds)->toBe('2.5000')
        ->and($bet->actual_return)->toBe('25.00');
});
