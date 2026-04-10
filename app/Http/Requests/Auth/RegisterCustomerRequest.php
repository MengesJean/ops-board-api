<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterCustomerRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customer,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Full display name of the customer.',
                'example' => 'Ada Lovelace',
            ],
            'email' => [
                'description' => 'Unique email address used as the account identifier.',
                'example' => 'ada@example.com',
            ],
            'password' => [
                'description' => 'Plain-text password. Must satisfy the default Laravel password policy.',
                'example' => 'correct-horse-battery-staple',
            ],
            'password_confirmation' => [
                'description' => 'Must match `password`.',
                'example' => 'correct-horse-battery-staple',
            ],
        ];
    }
}
