<?php

namespace App\Http\Requests\Bankroll;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class BankrollRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $currency = $this->input('currency');

        $this->merge([
            'currency' => is_string($currency) ? Str::upper($currency) : $currency,
        ]);
    }

    /** @return array<string, array<int, string|Unique>> */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('bankrolls', 'name')->where('user_id', $this->user()->id),
            ],
            'currency' => ['required', 'string', 'size:3'],
            'starting_balance' => ['required', 'numeric', 'decimal:2', 'max:9999999999.99'],
            'is_active' => ['boolean'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
