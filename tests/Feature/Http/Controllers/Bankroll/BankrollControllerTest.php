<?php

use App\Enums\Bankroll\TransactionType;
use App\Models\User;

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
