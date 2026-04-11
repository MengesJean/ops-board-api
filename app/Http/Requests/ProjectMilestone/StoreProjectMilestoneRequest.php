<?php

namespace App\Http\Requests\ProjectMilestone;

use App\Enums\MilestoneStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectMilestoneRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(MilestoneStatus::class)],
            'due_date' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Short label of the milestone (e.g. "Design ready").',
                'example' => 'Design ready',
            ],
            'description' => [
                'description' => 'Optional long-form details about the milestone.',
                'example' => 'All wireframes signed off by stakeholders.',
            ],
            'status' => [
                'description' => 'Milestone status. One of `pending`, `in_progress`, `done`.',
                'example' => 'pending',
            ],
            'due_date' => [
                'description' => 'Optional target date (ISO 8601 date).',
                'example' => '2026-06-15',
            ],
        ];
    }
}
