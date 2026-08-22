<?php

namespace App\Enums\Bankroll;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
    case STAKE = 'stake';
    case PAYOUT = 'payout';
    case REFUND = 'refund';
    case ADJUSTMENT_CREDIT = 'adjustment_credit';
    case ADJUSTMENT_DEBIT = 'adjustment_debit';
    case BONUS = 'bonus';
}
