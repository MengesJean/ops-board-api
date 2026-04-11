<?php

namespace App\Http\Requests\ProjectMilestone;

use App\Enums\MilestoneStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectMilestoneRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'status' => ['sometimes', 'required', Rule::enum(MilestoneStatus::class)],
            'due_date' => ['sometimes', 'nullable', 'date'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Short label of the milestone.',
                'example' => 'Design ready',
            ],
            'description' => [
                'description' => 'Optional long-form details.',
                'example' => 'All wireframes signed off by stakeholders.',
            ],
            'status' => [
                'description' => 'Milestone status. One of `pending`, `in_progress`, `done`. Setting it to `done` automatically stamps `completed_at`; moving back to another status clears it.',
                'example' => 'in_progress',
            ],
            'due_date' => [
                'description' => 'Optional target date (ISO 8601 date).',
                'example' => '2026-06-15',
            ],
        ];
    }
}
