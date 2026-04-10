<?php

namespace App\Http\Requests\Client;

use App\Enums\ClientStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                // Email uniqueness is scoped to the authenticated customer so the same
                // email can appear in the client table across different customers.
                Rule::unique('client', 'email')
                    ->where(fn ($query) => $query->where('customer_id', $this->user()?->id)),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::enum(ClientStatus::class)],
            'notes' => ['nullable', 'string', 'max:5000'],
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
                'example' => 'lead',
            ],
            'notes' => [
                'description' => 'Optional free-form notes about the client.',
                'example' => 'Met at the Q2 sales conference.',
            ],
        ];
    }
}
