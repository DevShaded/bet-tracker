<?php

namespace App\Http\Requests\Bets;

use Illuminate\Foundation\Http\FormRequest;

class BetComponentRequest extends FormRequest
{
    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'bet_id' => ['required', 'exists:bets,id'],
            'type' => ['required'],
            'stake' => ['required', 'decimal:2'],
            'odds' => ['required', 'decimal:4'],
            'result' => ['nullable'],
            'return_amount' => ['nullable', 'decimal:2'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
