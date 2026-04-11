<?php

namespace App\Http\Requests\Client;

use App\Enums\ClientStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexClientRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::enum(ClientStatus::class)],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'search' => [
                'description' => 'Case-insensitive partial match on `name` or `company_name`.',
                'example' => 'acme',
            ],
            'status' => [
                'description' => 'Filter by lifecycle status.',
                'example' => 'active',
            ],
            'per_page' => [
                'description' => 'Number of results per page (1–100). Defaults to 15.',
                'example' => 15,
            ],
        ];
    }
}
