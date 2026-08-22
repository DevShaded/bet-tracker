<?php

namespace App\Http\Requests\Tipster;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $user_id
 * @property mixed $name
 * @property mixed $default_stake_percentage
 */
class TipCategoryRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required'],
            'default_stake_percentage' => ['nullable', 'decimal:2', 'min:0', 'max:100'],
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
