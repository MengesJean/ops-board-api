<?php

namespace App\Http\Requests\Client;

use App\Enums\ClientStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'company_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('client', 'email')
                    ->where(fn ($query) => $query->where('customer_id', $this->user()?->id))
                    ->ignore($this->route('client')?->id),
            ],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'status' => ['sometimes', 'required', Rule::enum(ClientStatus::class)],
            'notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Primary contact or display name for the client.',
                'example' => 'Grace Hopper',
            ],
            'company_name' => [
                'description' => 'Optional company or organization the client belongs to.',
                'example' => 'Hopper Industries',
            ],
            'email' => [
                'description' => 'Unique (per customer) contact email for the client.',
                'example' => 'grace@hopper.test',
            ],
            'phone' => [
                'description' => 'Optional phone number.',
                'example' => '+1 555 0123',
            ],
            'status' => [
                'description' => 'Lifecycle status. One of `lead`, `active`, `inactive`.',
                'example' => 'active',
            ],
            'notes' => [
                'description' => 'Optional free-form notes about the client.',
                'example' => 'Closed their first contract in Q2.',
            ],
        ];
    }
}
