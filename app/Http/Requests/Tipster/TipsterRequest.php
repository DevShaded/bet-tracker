<?php

namespace App\Http\Requests\Tipster;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipsterRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'name' => [
                'required',
                Rule::unique('tipsters', 'name')->where('user_id', $this->input('user_id')),
            ],
            'source' => ['nullable'],
            'notes' => ['nullable'],
            'is_active' => ['boolean'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
