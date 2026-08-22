<?php

namespace App\Bet;

enum BetStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case WON = 'won';
    case LOST = 'lost';
    case PARTIALLY_WON = 'partially_won';
    case VOID = 'void';
    case CANCELLED = 'cancelled';
    case CASHOUT = 'cashout';
}
