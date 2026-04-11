<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectHealth;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexProjectRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $customerId = $this->user()?->id;

        return [
            'search' => ['nullable', 'string', 'max:255'],
            'client_id' => [
                'nullable',
                'integer',
                Rule::exists('client', 'id')
                    ->where(fn ($query) => $query->where('customer_id', $customerId)),
            ],
            'status' => ['nullable', Rule::enum(ProjectStatus::class)],
            'priority' => ['nullable', Rule::enum(ProjectPriority::class)],
            'health' => ['nullable', Rule::enum(ProjectHealth::class)],
            'sort' => ['nullable', 'in:due_date,updated_at,created_at'],
            'direction' => ['nullable', 'in:asc,desc'],
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
                'description' => 'Case-insensitive partial match on `name` or `reference`.',
                'example' => 'website',
            ],
            'client_id' => [
                'description' => 'Restrict results to projects belonging to a specific client owned by the authenticated customer.',
                'example' => 1,
            ],
            'status' => [
                'description' => 'Filter by project status. One of `draft`, `planned`, `active`, `on_hold`, `completed`, `cancelled`.',
                'example' => 'active',
            ],
            'priority' => [
                'description' => 'Filter by priority. One of `low`, `medium`, `high`.',
                'example' => 'high',
            ],
            'health' => [
                'description' => 'Filter by project health. One of `good`, `warning`, `critical`.',
                'example' => 'good',
            ],
            'sort' => [
                'description' => 'Sort column. One of `due_date`, `updated_at`, `created_at`. Defaults to `id`.',
                'example' => 'due_date',
            ],
            'direction' => [
                'description' => 'Sort direction. `asc` or `desc`. Defaults to `desc`.',
                'example' => 'asc',
            ],
            'per_page' => [
                'description' => 'Number of results per page (1–100). Defaults to 15.',
                'example' => 15,
            ],
        ];
    }
}
