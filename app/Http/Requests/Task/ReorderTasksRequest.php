<?php

namespace App\Http\Requests\Task;

use App\Models\Project;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReorderTasksRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Project|null $project */
        $project = $this->route('project');

        return [
            'task_ids' => ['required', 'array', 'min:1'],
            'task_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('task', 'id')
                    ->where(fn ($query) => $query->where('project_id', $project?->id)),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var Project|null $project */
            $project = $this->route('project');

            if ($project === null) {
                return;
            }

            // Reorder must cover the project's tasks exactly: no missing,
            // no extras. Otherwise the resulting positions would be inconsistent.
            $expected = $project->tasks()->count();
            $given = is_array($this->input('task_ids')) ? count($this->input('task_ids')) : 0;

            if ($given !== $expected) {
                $validator->errors()->add(
                    'task_ids',
                    "The task_ids list must contain exactly {$expected} entries (one per existing task)."
                );
            }
        });
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'task_ids' => [
                'description' => 'Ordered list of task IDs. Must contain **all** of the project tasks, in the desired final order.',
                'example' => [12, 7, 23, 4],
            ],
        ];
    }
}
