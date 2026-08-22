<?php

namespace App\Http\Requests\Bookmaker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BookmakerRequest extends FormRequest
{
    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required'],
            'minimum_stake' => ['nullable', 'decimal:2'],
            'currency' => ['nullable', 'string', 'size:3'],
            'is_active' => ['boolean'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'currency' => $this->currency === null ? null : Str::upper((string) $this->currency),
        ]);
    }
}
