<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectHealth;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $customerId = $this->user()?->id;

        return [
            // The exists rule is scoped to the authenticated customer's clients,
            // so a payload pointing to a foreign client_id fails validation (422)
            // before any policy check runs.
            'client_id' => [
                'required',
                'integer',
                Rule::exists('client', 'id')
                    ->where(fn ($query) => $query->where('customer_id', $customerId)),
            ],
            'name' => ['required', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'priority' => ['required', Rule::enum(ProjectPriority::class)],
            'health' => ['required', Rule::enum(ProjectHealth::class)],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'client_id' => [
                'description' => 'ID of the client this project belongs to. Must be owned by the authenticated customer.',
                'example' => 1,
            ],
            'name' => [
                'description' => 'Display name of the project.',
                'example' => 'Acme website redesign',
            ],
            'reference' => [
                'description' => 'Optional internal reference code.',
                'example' => 'PRJ-2026-001',
            ],
            'description' => [
                'description' => 'Optional long-form description of the project scope.',
                'example' => 'Full marketing site redesign with CMS migration.',
            ],
            'status' => [
                'description' => 'Lifecycle status. One of `draft`, `planned`, `active`, `on_hold`, `completed`, `cancelled`.',
                'example' => 'planned',
            ],
            'priority' => [
                'description' => 'Priority level. One of `low`, `medium`, `high`.',
                'example' => 'high',
            ],
            'health' => [
                'description' => 'Current health indicator. One of `good`, `warning`, `critical`.',
                'example' => 'good',
            ],
            'start_date' => [
                'description' => 'Optional start date (ISO 8601 date).',
                'example' => '2026-05-01',
            ],
            'due_date' => [
                'description' => 'Optional due date (ISO 8601 date). Must be on or after `start_date` when both are provided.',
                'example' => '2026-09-30',
            ],
            'budget' => [
                'description' => 'Optional budget amount (decimal, ≥ 0).',
                'example' => 25000,
            ],
            'notes' => [
                'description' => 'Optional free-form notes.',
                'example' => 'Kick-off meeting scheduled for the first week.',
            ],
        ];
    }
}
