<?php

namespace App\Http\Requests\Bets;

use App\Bet\BetStatus;
use App\Bet\BetType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BetRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'bankroll_id' => ['required', 'exists:bankrolls,id'],
            'bookmaker_id' => ['required', 'exists:bookmakers,id'],
            'tipster_id' => ['nullable', 'exists:tipsters,id'],
            'tip_category_id' => ['nullable', 'exists:tip_categories,id'],
            'bet_type' => ['required', Rule::enum(BetType::class)],
            'status' => ['required', Rule::enum(BetStatus::class)],
            'stake' => ['required', 'decimal:2'],
            'unit_stake' => ['nullable', 'decimal:2'],
            'combined_odds' => ['nullable', 'decimal:4'],
            'actual_return' => ['nullable', 'decimal:2'],
            'placed_at' => ['nullable', 'date'],
            'settled_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'max:1000'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
