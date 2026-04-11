<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectHealth;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $customerId = $this->user()?->id;

        return [
            // The exists scope still runs when client_id is present, blocking
            // re-assignment to a client owned by another customer.
            'client_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('client', 'id')
                    ->where(fn ($query) => $query->where('customer_id', $customerId)),
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'reference' => ['sometimes', 'nullable', 'string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'status' => ['sometimes', 'required', Rule::enum(ProjectStatus::class)],
            'priority' => ['sometimes', 'required', Rule::enum(ProjectPriority::class)],
            'health' => ['sometimes', 'required', Rule::enum(ProjectHealth::class)],
            'start_date' => ['sometimes', 'nullable', 'date'],
            'due_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
            'budget' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
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
                'description' => 'Lifecycle status.',
                'example' => 'active',
            ],
            'priority' => [
                'description' => 'Priority level.',
                'example' => 'medium',
            ],
            'health' => [
                'description' => 'Current health indicator.',
                'example' => 'warning',
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
                'example' => 30000,
            ],
            'notes' => [
                'description' => 'Optional free-form notes.',
                'example' => 'Pushed go-live by two weeks.',
            ],
        ];
    }
}
