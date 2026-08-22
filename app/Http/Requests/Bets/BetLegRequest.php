<?php

namespace App\Http\Requests\Bets;

use Illuminate\Foundation\Http\FormRequest;

class BetLegRequest extends FormRequest
{
    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'bet_id' => ['required', 'exists:bets,id'],
            'sport' => ['required'],
            'competition' => ['nullable'],
            'venue' => ['nullable'],
            'selection' => ['required'],
            'market' => ['required'],
            'odds' => ['required', 'decimal:4'],
            'event_starts_at' => ['nullable', 'date'],
            'result' => ['nullable'],
            'settled_at' => ['nullable', 'date'],
            'sort_order' => ['required', 'integer'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
