<?php

namespace App\Http\Requests\Bankroll;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BankrollRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'currency' => $this->input('currency') === null
                ? null
                : Str::upper($this->input('currency')),
        ]);
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required'],
            'currency' => ['required', 'string', 'size:3'],
            'starting_balance' => ['required', 'decimal:2'],
            'is_active' => ['boolean'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
