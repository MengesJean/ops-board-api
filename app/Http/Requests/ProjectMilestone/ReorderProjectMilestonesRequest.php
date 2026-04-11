<?php

namespace App\Http\Requests\ProjectMilestone;

use App\Models\Project;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReorderProjectMilestonesRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Project|null $project */
        $project = $this->route('project');

        return [
            'milestone_ids' => ['required', 'array', 'min:1'],
            'milestone_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('project_milestone', 'id')
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

            // Reorder must cover the project's milestones exactly: no missing,
            // no extras. Otherwise the resulting positions would be inconsistent.
            $expected = $project->milestones()->count();
            $given = is_array($this->input('milestone_ids')) ? count($this->input('milestone_ids')) : 0;

            if ($given !== $expected) {
                $validator->errors()->add(
                    'milestone_ids',
                    "The milestone_ids list must contain exactly {$expected} entries (one per existing milestone)."
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
            'milestone_ids' => [
                'description' => 'Ordered list of milestone IDs. Must contain **all** of the project milestones, in the desired final order.',
                'example' => [12, 7, 23, 4],
            ],
        ];
    }
}
