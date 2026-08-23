<?php

namespace App\Queries\Bankroll;

use App\Enums\Bankroll\TransactionType;
use App\Enums\Bet\BetStatus;
use App\Models\Bankroll\Bankroll;
use App\Models\Bankroll\BankrollTransaction;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GetBankrollStatsQuery
{
    /**
     * @return array{
     *     active_bankrolls_count: int,
     *     by_currency: array<string, array{
     *         total_bankroll: float,
     *         net_change: float,
     *         net_change_percentage: float,
     *         today_change: array{amount: float, percentage: float},
     *         exposure_at_risk: array{amount: float, percentage: float}
     *     }>
     * }
     */
    public static function handle(User $user): array
    {
        $today = today();
        $tomorrow = $today->copy()->addDay();
        $statsByCurrency = [];

        foreach ($user->bankrolls()->distinct()->orderBy('currency')->pluck('currency') as $currency) {
            $availableBalance = self::getAvailableBalance($user, $currency);
            $pendingStakes = self::getPendingStakes($user, $currency);
            $totalBankroll = $availableBalance + $pendingStakes;
            $startingBalance = (float) $user->bankrolls()
                ->where('currency', $currency)
                ->sum('starting_balance');
            $netChange = $totalBankroll - $startingBalance;
            $todayChange = self::getTodayChange($user, $currency, $today, $tomorrow);
            $todayCashFlow = self::getTodayCashFlow($user, $currency, $today, $tomorrow);
            $yesterdayClosingBalance = $totalBankroll - $todayChange - $todayCashFlow;

            $statsByCurrency[$currency] = [
                'total_bankroll' => round($totalBankroll, 2),
                'net_change' => round($netChange, 2),
                'net_change_percentage' => round(self::calculatePercentage($netChange, $startingBalance), 2),
                'today_change' => [
                    'amount' => round($todayChange, 2),
                    'percentage' => round(self::calculatePercentage($todayChange, $yesterdayClosingBalance), 2),
                ],
                'exposure_at_risk' => [
                    'amount' => round($pendingStakes, 2),
                    'percentage' => round(self::calculatePercentage($pendingStakes, $totalBankroll), 2),
                ],
            ];
        }

        return [
            'active_bankrolls_count' => $user->bankrolls()->where('is_active', true)->count(),
            'by_currency' => $statsByCurrency,
        ];
    }

    private static function getAvailableBalance(User $user, string $currency): float
    {
        return (float) BankrollTransaction::query()
            ->whereIn('bankroll_id', self::bankrollIdsForCurrency($user, $currency))
            ->toBase()
            ->rawValue(
                'COALESCE(SUM(CASE WHEN type IN (?, ?, ?) THEN -amount ELSE amount END), 0)',
                [
                    TransactionType::WITHDRAWAL->value,
                    TransactionType::STAKE->value,
                    TransactionType::ADJUSTMENT_DEBIT->value,
                ],
            );
    }

    private static function getPendingStakes(User $user, string $currency): float
    {
        return (float) $user->bets()
            ->whereIn('bankroll_id', self::bankrollIdsForCurrency($user, $currency))
            ->where('status', BetStatus::PENDING->value)
            ->sum('stake');
    }

    private static function getTodayChange(
        User $user,
        string $currency,
        CarbonInterface $today,
        CarbonInterface $tomorrow,
    ): float {
        return (float) $user->bets()
            ->whereIn('bankroll_id', self::bankrollIdsForCurrency($user, $currency))
            ->where('settled_at', '>=', $today)
            ->where('settled_at', '<', $tomorrow)
            ->whereIn('status', [
                BetStatus::WON->value,
                BetStatus::LOST->value,
                BetStatus::PARTIALLY_WON->value,
                BetStatus::CASHOUT->value,
            ])
            ->toBase()
            ->rawValue('COALESCE(SUM(COALESCE(actual_return, 0) - stake), 0)');
    }

    private static function getTodayCashFlow(
        User $user,
        string $currency,
        CarbonInterface $today,
        CarbonInterface $tomorrow,
    ): float {
        return (float) BankrollTransaction::query()
            ->whereIn('bankroll_id', self::bankrollIdsForCurrency($user, $currency))
            ->where('occurred_at', '>=', $today)
            ->where('occurred_at', '<', $tomorrow)
            ->whereIn('type', [
                TransactionType::DEPOSIT->value,
                TransactionType::WITHDRAWAL->value,
                TransactionType::ADJUSTMENT_CREDIT->value,
                TransactionType::ADJUSTMENT_DEBIT->value,
                TransactionType::BONUS->value,
            ])
            ->toBase()
            ->rawValue(
                'COALESCE(SUM(CASE WHEN type IN (?, ?) THEN -amount ELSE amount END), 0)',
                [
                    TransactionType::WITHDRAWAL->value,
                    TransactionType::ADJUSTMENT_DEBIT->value,
                ],
            );
    }

    /** @return HasMany<Bankroll, User> */
    private static function bankrollIdsForCurrency(User $user, string $currency): HasMany
    {
        return $user->bankrolls()
            ->where('currency', $currency)
            ->select('id');
    }

    private static function calculatePercentage(float $amount, float $balance): float
    {
        return $balance > 0 ? ($amount / $balance) * 100 : 0;
    }
}
