<?php

namespace App\Actions\Bankroll;

use App\Enums\Bankroll\TransactionType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class StoreBankrollAction
{
    /**
     * Store a new bankroll for the given user
     * @throws Throwable
     */
    public static function handle(User $user, mixed $data): void
    {
        DB::transaction(function () use ($user, $data) {
            $bankroll = $user->bankrolls()->create($data);

            $bankroll->transactions()->create([
                'amount' => $data['starting_balance'],
                'description' => 'Initial balance',
                'type' => TransactionType::DEPOSIT,
                'occurred_at' => now(),
                'bankroll_id' => $bankroll->id,
                'bet_id' => null,
            ]);
        });
    }
}
