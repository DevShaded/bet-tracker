<?php

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
})->with([
    'active' => true,
    'inactive' => false,
]);
