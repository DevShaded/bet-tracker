<?php

use App\Models\Bankroll\Bankroll;
use App\Models\Bankroll\BankrollTransaction;
use App\Models\Bets\Bet;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('bankroll factory creates its user relation', function () {
    $bankroll = Bankroll::factory()->create([
        'starting_balance' => '100.00',
    ]);

    expect($bankroll->user)
        ->toBeInstanceOf(User::class)
        ->and($bankroll->user_id)->toBe($bankroll->user->id)
        ->and($bankroll->user())->toBeInstanceOf(BelongsTo::class);
});

test('bankroll defines its child relations', function () {
    $bankroll = Bankroll::factory()->make();

    expect($bankroll->bets())
        ->toBeInstanceOf(HasMany::class)
        ->and($bankroll->bets()->getRelated())->toBeInstanceOf(Bet::class)
        ->and($bankroll->transactions())->toBeInstanceOf(HasMany::class)
        ->and($bankroll->transactions()->getRelated())->toBeInstanceOf(BankrollTransaction::class);
});

test('user defines its bankroll relation', function () {
    $user = new User;

    expect($user->bankrolls())
        ->toBeInstanceOf(HasMany::class)
        ->and($user->bankrolls()->getRelated())->toBeInstanceOf(Bankroll::class);
});

test('bankroll casts its active state to boolean', function () {
    $bankroll = Bankroll::factory()->make([
        'is_active' => 1,
    ]);

    expect($bankroll->is_active)
        ->toBeTrue()
        ->and($bankroll->getCasts()['is_active'])->toBe('boolean');
});

test('bankroll casts its starting balance to two decimals', function () {
    $bankroll = Bankroll::factory()->make([
        'starting_balance' => '100',
    ]);

    expect($bankroll->starting_balance)
        ->toBe('100.00')
        ->and($bankroll->getCasts()['starting_balance'])->toBe('decimal:2');
});

test('bankroll transaction casts its amount to two decimals', function () {
    $transaction = new BankrollTransaction([
        'amount' => '10',
    ]);

    expect($transaction->amount)
        ->toBe('10.00')
        ->and($transaction->getCasts()['amount'])->toBe('decimal:2');
});
