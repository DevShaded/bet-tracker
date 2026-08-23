<?php

namespace App\Actions\Bankroll;

use App\Enums\Bankroll\TransactionType;
use App\Models\Bankroll\Bankroll;
use App\Models\Bankroll\BankrollTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetBankrollAction
{
    /** @return Collection<int, Bankroll> */
    public static function handle(User $user): Collection
    {
        $debitTypes = [
            TransactionType::WITHDRAWAL->value,
            TransactionType::STAKE->value,
            TransactionType::ADJUSTMENT_DEBIT->value,
        ];

        $currentBalance = BankrollTransaction::query()
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN type IN (?, ?, ?) THEN -amount ELSE amount END), 0)',
                $debitTypes,
            )
            ->whereColumn('bankroll_id', 'bankrolls.id');

        return $user->bankrolls()
            ->select('bankrolls.*')
            ->addSelect(['current_balance' => $currentBalance])
            ->withCasts(['current_balance' => 'float'])
            ->latest()
            ->get();
    }
}
