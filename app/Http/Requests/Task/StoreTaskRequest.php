<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Project|null $project */
        $project = $this->route('project');

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'due_date' => ['nullable', 'date'],
            'project_milestone_id' => [
                'nullable',
                'integer',
                Rule::exists('project_milestone', 'id')
                    ->where(fn ($query) => $query->where('project_id', $project?->id)),
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Short label of the task (e.g. "Write the technical brief").',
                'example' => 'Write the technical brief',
            ],
            'description' => [
                'description' => 'Optional long-form details about the task.',
                'example' => 'Cover the API contract, the data flow and the rollout plan.',
            ],
            'status' => [
                'description' => 'Task status. One of `todo`, `in_progress`, `done`. Setting it to `done` automatically stamps `completed_at`.',
                'example' => 'todo',
            ],
            'priority' => [
                'description' => 'Task priority. One of `low`, `medium`, `high`.',
                'example' => 'medium',
            ],
            'due_date' => [
                'description' => 'Optional target date (ISO 8601 date).',
                'example' => '2026-06-15',
            ],
            'project_milestone_id' => [
                'description' => 'Optional milestone of the **same** project this task belongs to.',
                'example' => null,
            ],
        ];
    }
}
