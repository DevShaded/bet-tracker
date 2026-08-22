<?php

namespace App\Http\Requests\Bets;

use Illuminate\Foundation\Http\FormRequest;

class EachwayTermRequest extends FormRequest
{
    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'bet_id' => ['required', 'exists:bets,id'],
            'place_fraction' => ['required', 'decimal:4'],
            'places' => ['required', 'integer', 'min:0', 'max:65535'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
