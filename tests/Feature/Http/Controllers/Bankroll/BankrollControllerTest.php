<?php

use App\Enums\Bankroll\TransactionType;
use App\Enums\Bet\BetStatus;
use App\Models\Bankroll\BankrollTransaction;
use App\Models\Bets\Bet;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('index groups bankroll stats by currency', function () {
    $user = User::factory()->create();
    $bankroll = $user->bankrolls()->create([
        'name' => 'Main bankroll',
        'currency' => 'USD',
        'starting_balance' => '1000.00',
        'is_active' => true,
    ]);
    $bet = Bet::factory()->create([
        'user_id' => $user->id,
        'bankroll_id' => $bankroll->id,
        'status' => BetStatus::PENDING,
        'stake' => '100.00',
        'settled_at' => null,
    ]);
    $settledBet = Bet::factory()->create([
        'user_id' => $user->id,
        'bankroll_id' => $bankroll->id,
        'status' => BetStatus::WON,
        'stake' => '100.00',
        'actual_return' => '150.00',
        'settled_at' => now(),
    ]);

    BankrollTransaction::factory()->create([
        'bankroll_id' => $bankroll->id,
        'bet_id' => null,
        'type' => TransactionType::DEPOSIT,
        'amount' => '1000.00',
        'occurred_at' => today()->subDay()->setTime(12, 0),
    ]);
    BankrollTransaction::factory()->create([
        'bankroll_id' => $bankroll->id,
        'bet_id' => $bet->id,
        'type' => TransactionType::STAKE,
        'amount' => '100.00',
    ]);
    BankrollTransaction::factory()->create([
        'bankroll_id' => $bankroll->id,
        'bet_id' => null,
        'type' => TransactionType::ADJUSTMENT_CREDIT,
        'amount' => '210.50',
        'occurred_at' => today()->subDay()->setTime(12, 0),
    ]);
    BankrollTransaction::factory()->create([
        'bankroll_id' => $bankroll->id,
        'bet_id' => $settledBet->id,
        'type' => TransactionType::STAKE,
        'amount' => '100.00',
    ]);
    BankrollTransaction::factory()->create([
        'bankroll_id' => $bankroll->id,
        'bet_id' => $settledBet->id,
        'type' => TransactionType::PAYOUT,
        'amount' => '150.00',
    ]);
    $euroBankroll = $user->bankrolls()->create([
        'name' => 'Euro bankroll',
        'currency' => 'EUR',
        'starting_balance' => '400.00',
        'is_active' => true,
    ]);

    BankrollTransaction::factory()->create([
        'bankroll_id' => $euroBankroll->id,
        'bet_id' => null,
        'type' => TransactionType::DEPOSIT,
        'amount' => '500.00',
        'occurred_at' => today()->subDay()->setTime(12, 0),
    ]);

    $this->actingAs($user)
        ->get(route('bankroll.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('bankroll/Index')
            ->where('stats.active_bankrolls_count', 2)
            ->where('stats.by_currency.USD.total_bankroll', 1260.5)
            ->where('stats.by_currency.USD.net_change', 260.5)
            ->where('stats.by_currency.USD.net_change_percentage', 26.05)
            ->where('stats.by_currency.USD.today_change.amount', 50)
            ->where('stats.by_currency.USD.today_change.percentage', 4.13)
            ->where('stats.by_currency.USD.exposure_at_risk.amount', 100)
            ->where('stats.by_currency.USD.exposure_at_risk.percentage', 7.93)
            ->where('stats.by_currency.EUR.total_bankroll', 500)
            ->where('stats.by_currency.EUR.net_change', 100)
            ->where('stats.by_currency.EUR.net_change_percentage', 25)
            ->where('stats.by_currency.EUR.today_change.amount', 0)
            ->where('stats.by_currency.EUR.today_change.percentage', 0)
            ->where('stats.by_currency.EUR.exposure_at_risk.amount', 0)
            ->where('stats.by_currency.EUR.exposure_at_risk.percentage', 0),
        );
});

test('user can create active and inactive bankrolls', function (bool $isActive) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('bankroll.store'), [
            'name' => 'Main bankroll',
            'currency' => 'USD',
            'starting_balance' => '1000.00',
            'is_active' => $isActive,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('bankroll.index'));

    $this->assertDatabaseHas('bankrolls', [
        'user_id' => $user->id,
        'name' => 'Main bankroll',
        'currency' => 'USD',
        'starting_balance' => '1000.00',
        'is_active' => $isActive,
    ]);

    $bankroll = $user->bankrolls()->firstOrFail();

    $this->assertDatabaseHas('bankroll_transactions', [
        'bankroll_id' => $bankroll->id,
        'type' => TransactionType::DEPOSIT,
        'amount' => '1000.00',
        'description' => 'Initial balance',
        'occurred_at' => now(),
        'bet_id' => null,
    ]);
})->with([
    'active' => true,
    'inactive' => false,
]);

test('user cannot create duplicate bankrolls', function () {
    $user = User::factory()->create();

    $user->bankrolls()->create([
        'name' => 'Main bankroll',
        'currency' => 'USD',
        'starting_balance' => '1000.00',
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('bankroll.store'), [
            'name' => 'Main bankroll',
            'currency' => 'USD',
            'starting_balance' => '1000.00',
            'is_active' => false,
        ]);

    $response
        ->assertSessionHasErrors(['name']);

    expect($user->bankrolls()->count())->toBe(1);
});

test('different users can have bankrolls with the same name', function () {
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();

    $firstUser->bankrolls()->create([
        'name' => 'Main bankroll',
        'currency' => 'USD',
        'starting_balance' => '1000.00',
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($secondUser)
        ->post(route('bankroll.store'), [
            'name' => 'Main bankroll',
            'currency' => 'USD',
            'starting_balance' => '1000.00',
            'is_active' => true,
        ]);

    $response->assertSessionHasNoErrors();

    expect($secondUser->bankrolls()->count())->toBe(1);
});
