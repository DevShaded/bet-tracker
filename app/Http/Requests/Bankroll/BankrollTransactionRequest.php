<?php

namespace App\Http\Requests\Bankroll;

use App\Bankroll\TransactionType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankrollTransactionRequest extends FormRequest
{
    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'bankroll_id' => ['required', 'exists:bankrolls,id'],
            'bet_id' => ['nullable', 'exists:bets,id'],
            'type' => ['required', Rule::enum(TransactionType::class)],
            'amount' => ['required', 'decimal:2', 'min:0.01'],
            'description' => ['nullable', 'max:1000'],
            'occurred_at' => ['required', 'date'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
