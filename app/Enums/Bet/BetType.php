<?php

namespace App\Enums\Bet;

enum BetType: string
{
    case SINGLE = 'single';
    case EACH_WAY = 'each_way';
    case DOUBLE = 'double';
    case ACCUMULATOR = 'accumulator';
    case SYSTEM = 'system';
}
